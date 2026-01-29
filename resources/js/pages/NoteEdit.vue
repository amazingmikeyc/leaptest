<script setup lang="ts">

import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { notes, noteedit } from '@/routes';
import { ref, watchEffect } from 'vue';
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea';
import { router} from '@inertiajs/vue3';
import { Alert, AlertTitle } from '@/components/ui/alert';
import { AlertDialog } from '@/components/ui/alert-dialog';

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

const errors = ref(
    {}
)

const headers = {
    "Content-Type": "application/json",
    'X-Requested-With': 'XMLHttpRequest'
}

let disableForm = true;
let disableSave = false;

if (props.id !== undefined) {
    watchEffect(async () => {
        const url = '/api/notes/' + props.id;
        const response = await ((await fetch(url)).json());
        noteData.value = response.data;
        disableForm = false;
    });
} else {
    disableForm = false;
}

async function  save() {

    let url: string = '/api/notes';
    let method: string = 'POST';

    if (noteData.value.id) {
        url = '/api/notes/' + noteData.value.id;
        method = 'PUT';
    }

    const options = {
        method: method,
        body: JSON.stringify({
            name: noteData.value.name,
            content: noteData.value.content
        }),
        headers: headers,
    }

    disableSave = true;
    const response = await (fetch(url, options));
    if (response.status === 200 || response.status === 201) {
        noteData.value = (await response.json()).data;
        errors.value = {};
        disableSave = false;
        //forward to correct URL
        router.get(noteedit(noteData.value.id));
        return;
    }
    //validation error
    if (response.status === 422) {
        const responseJson = await response.json();
        errors.value = responseJson.errors;
        disableSave = false;
    }

}

</script>

<template>
    <Head title="Notes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >

            <div class="grid w-full items-center gap-4">
                <div class="flex flex-col space-y-1.5">
                    <Label for="name">Note Name</Label>
                    <Input id="name" type="text" v-model="noteData.name" :disabled="disableForm"/>
                    <Label>{{ errors.name }}</Label>
                </div>
                <div class="flex flex-col space-y-1.5">
                    <div class="flex items-center">
                        <Label for="content">Note Content</Label>
                    </div>
                    <Textarea  id="content" type="textarea" v-model="noteData.content" :disabled="disableForm"  />
                    <Label>{{ errors.content }}</Label>
                </div>
            </div>

            <Button v-if="noteData.id" variant="outline" @click="save" :disabled="disableForm|disableSave">Save</Button>
            <Button v-else variant="outline" @click="save" :disabled="disableForm|disableSave">Create</Button>

        </div>

    </AppLayout>
</template>

<style scoped>

</style>
