<script setup lang="ts">


import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from '@/components/ui/table'

/**
 * {
 *         id: int,
 *         name: String,
 *         content: String,
 *         date: String,
 *     }
 */
const props = defineProps({
    notesData: <{
        id: int,
        name: String,
        content: String,
        date: String,
    }>[]
})

const emit = defineEmits(['refresh']);

import { Pencil, View } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import {noteview, noteedit} from '@/routes';
import { Link } from '@inertiajs/vue3';

async function deleteNote(id: string) {

    if (!confirm('Are you sure you want to delete this note?')) {
        return
    }

    const headers = {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
    };
    const method = 'DELETE';
    const options = {
        method: method,
        headers: headers
    };
    await fetch(`/api/notes/${id}`, options);

    emit('refresh');
}

</script>

<template>

    <Table>
        <TableHeader>
            <TableRow>
                <TableCell>Note Name</TableCell>
                <TableCell>Actions</TableCell>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="note in notesData" :key="note.id">
                <TableCell>{{ note.name }}</TableCell>
                <TableCell>
                    <Button ><View /><Link :href="noteview.url(note.id )"> View</Link></Button>
                    <Button variant="secondary"><Pencil /> <Link :href="noteedit.url(note.id)">Edit</Link></Button>
                    <Button variant="destructive" @click="deleteNote(note.id)">Delete</Button>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>

</template>

<style scoped>

</style>
