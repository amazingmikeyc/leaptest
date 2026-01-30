<script setup lang="ts">

import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { notes, noteedit } from '@/routes';
import { ref, watchEffect } from 'vue';
import { Button } from '@/components/ui/button'
import { Pencil, Undo2 } from 'lucide-vue-next'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'ViewNotes',
        href: notes().url,
    },
];

const props = defineProps({
    id: String
})

const noteData = ref(
    {
        id: 0,
        name: '',
        content: '',
        created_at: Date.now(),
        updated_at: Date.now(),
    }
);


watchEffect(async () => {
    const url = '/api/notes/' + props.id;
    const response = await ((await fetch(url)).json());
    noteData.value = response.data;
});

</script>

<template>
    <Head title="Notes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Button><Pencil></Pencil><Link :href="noteedit.url(noteData.id)">Edit</Link></Button>

            <h2>{{ noteData.name }}</h2>

                <div>
                    {{ noteData.content }}
                </div>
                <div>
                    <p>Created at: {{ noteData.created_at }}</p>
                    <p>Updated at: {{ noteData.updated_at }} </p>
                </div>

            <Button variant="outline"><Undo2 /><Link :href="notes.url()">Back</Link></Button>
        </div>

    </AppLayout>
</template>

<style scoped>

</style>
