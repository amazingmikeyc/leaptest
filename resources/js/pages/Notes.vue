<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { notecreate, noteedit, notes } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import NotesList from '@/components/NotesList.vue';
import { ref, watchEffect } from 'vue';
import { Button } from '@/components/ui/button';
import { CirclePlus, RotateCcw } from 'lucide-vue-next'
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notes',
        href: notes().url,
    },
];

const notesData = ref([]);

watchEffect(refreshData);

async function refreshData() {
    const url = '/api/notes';
    const response = await ((await fetch(url)).json());
    notesData.value = response.data;
}

</script>

<template>
    <Head title="Notes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
            >
                <Button><CirclePlus /><Link :href="notecreate()">New Note</Link></Button>
                <Button variant="secondary" @click="refreshData"><RotateCcw />Refresh List</Button>

                <NotesList :notesData="notesData" @refresh="refreshData" />
            </div>
        </div>
    </AppLayout>
</template>
