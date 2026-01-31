FROM shinsenter/laravel:latest
# Update npm to the absolute latest version independently
# 1. Install prerequisites
RUN apt-get update && apt-get install -y ca-certificates curl gnupg

# 2. Add the NodeSource GPG key
RUN mkdir -p /etc/apt/keyrings && \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg

# 3. Create the repository entry (targeting Node 22)
RUN NODE_MAJOR=22 && \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list

# 4. PIN the repository (This is the critical step you were missing)
# This tells apt to prioritize the NodeSource version over the Debian default
RUN echo "Package: nodejs" > /etc/apt/preferences.d/nodesource && \
    echo "Pin: origin deb.nodesource.com" >> /etc/apt/preferences.d/nodesource && \
    echo "Pin-Priority: 1001" >> /etc/apt/preferences.d/nodesource

# 5. Now install - it will correctly pick up version 22
RUN apt-get update && apt-get install -y nodejs && \
    npm install -g npm@latest && \
    rm -rf /var/lib/apt/lists/*
