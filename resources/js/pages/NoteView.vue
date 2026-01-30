<script setup lang="ts">

import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { notes, noteedit } from '@/routes';
import { ref, watchEffect } from 'vue';
import { Button } from '@/components/ui/button'
import { Pencil, Undo2, Frown } from 'lucide-vue-next'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';

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

const errorcode = ref('');

watchEffect(async () => {
    const url = '/api/notes/' + props.id;
    const response = (await fetch(url));
    errorcode.value = '';
    if (response.status === 200) {
        const json = await (response.json());
        noteData.value = json.data;
    } else {
        errorcode.value = response.status;
    }

});

</script>

<template>
    <Head title="Notes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >

        <div v-if="errorcode">
            <Alert v-if="errorcode==404">
                <Frown />
                <AlertTitle> Note not found</AlertTitle>
                <AlertDescription>404: There seems to have been an issue loading your note</AlertDescription>
            </Alert>
            <Alert v-else>
                <Frown />
                <AlertTitle> Error loading note</AlertTitle>
                <AlertDescription>{{ errorcode }}: There seems to have been an issue loading your note</AlertDescription>
            </Alert>

        </div>
        <div v-else>

                <Card>
                    <CardHeader>
                    <CardTitle>{{ noteData.name }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        {{ noteData.content }}



                    </CardContent>
                    <CardFooter>
                        <Button><Pencil></Pencil><Link :href="noteedit.url(noteData.id)">Edit</Link></Button>

                        &nbsp;Created at: {{ noteData.created_at }}
                    </CardFooter>

                </Card>
        </div>
        <Button variant="outline"><Undo2 /><Link :href="notes.url()">Back</Link></Button>
        </div>

    </AppLayout>
</template>

<style scoped>

</style>
