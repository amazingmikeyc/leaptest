<script setup lang="ts">

import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { notes, noteedit } from '@/routes';
import { ref, watchEffect } from 'vue';
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea';
import { router} from '@inertiajs/vue3';
import { Frown, Save, Undo2 } from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

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

const validationErrors = ref(
    {}
)

const loadingErrorCode = ref('');
const savingErrorCode = ref('');

const headers = {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
};

let disableForm = ref(true);
let disableSave = ref(false);

if (props.id !== undefined) {
    watchEffect(async () => {
        const url = '/api/notes/' + props.id;
        const response = (await fetch(url));
        loadingErrorCode.value = '';
        if (response.status === 200) {
            const json = await (response.json());
            noteData.value = json.data;
        } else {
            loadingErrorCode.value = response.status;
        }
        disableForm.value = false;
    });
} else {
    disableForm.value = false;
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
    savingErrorCode.value = '';
    disableSave.value = true;

    const response = await (fetch(url, options));
    if (response.status === 200 || response.status === 201) {
        noteData.value = (await response.json()).data;
        validationErrors.value = {};
        disableSave.value = false;
        //forward to correct URL
        router.get(noteedit(noteData.value.id));
        return;
    }
    //validation error
    if (response.status === 422) {
        const responseJson = await response.json();
        validationErrors.value = responseJson.errors;
        disableSave.value = false;
        return;
    }

    //default.
    savingErrorCode.value = response.status;
    const responseJson = await response.json();
    validationErrors.value = responseJson.errors;
    disableSave.value = false;
}

</script>

<template>
    <Head title="Notes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div v-if="loadingErrorCode">
                <Alert v-if="loadingErrorCode==404">
                    <Frown />
                    <AlertTitle> Note not found</AlertTitle>
                    <AlertDescription>404: There seems to have been an issue loading your note</AlertDescription>
                </Alert>
                <Alert v-else>
                    <Frown />
                    <AlertTitle> Error loading note</AlertTitle>
                    <AlertDescription>{{ loadingErrorCode }}: There seems to have been an issue loading your note</AlertDescription>
                </Alert>
            </div>

            <div v-else class="grid w-full items-center gap-4">
                <Alert v-if="savingErrorCode">
                    <Frown />
                    <AlertTitle> Error saving note</AlertTitle>
                    <AlertDescription>{{ savingErrorCode }}: There seems to have been an issue saving your note</AlertDescription>
                </Alert>


                <div class="flex flex-col space-y-1.5">
                    <Label for="name">Note Name</Label>
                    <Input id="name" type="text" v-model="noteData.name" :disabled="disableForm"/>
                    <Label>{{ validationErrors.name }}</Label>
                </div>
                <div class="flex flex-col space-y-1.5">
                    <div class="flex items-center">
                        <Label for="content">Note Content</Label>
                    </div>
                    <Textarea  id="content" type="textarea" v-model="noteData.content" :disabled="disableForm"  />
                    <Label>{{ validationErrors.content }}</Label>
                </div>

                <Button v-if="noteData.id"  @click="save" :disabled="disableForm|disableSave"><Save />Save</Button>
                <Button v-else  @click="save" :disabled="disableForm|disableSave"><Save />Create</Button>
            </div>

            <Button variant="outline"><Undo2 /><Link :href="notes.url()">Cancel</Link></Button>

        </div>

    </AppLayout>
</template>

<style scoped>

</style>
