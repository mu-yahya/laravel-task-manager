<script setup>
import TaskTable from '@/Components/TaskTable.vue';
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import Quote from '@/Components/Quote.vue'
import QR from '@/components/QR.vue';

let selected = ref(null)
// const selected = ref(null)
const statuses = ['All', 'Pending', 'In-Progress', 'Completed']
let selectedVal = 0;

function setValue(val) {
  selected.value = val
  selectedVal = val
  console.log('Selected:', val)
  // selectStatus
}
</script>

<template>
      <!-- Logout Button -->
      <div id="tasksPageContent">
        <Link
        href="/logout"
        method="post"
        as="button"
        class="text-sm bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 logoutButton"
      >
        Logout
      </Link>

      <h1 class="text-2xl font-bold">Tasks Page</h1>
      <Quote></Quote>

          <div class="p-4">

        <div class="flex justify-end space-x-2">
          <button
      @click="setValue(0)"
      class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-blue-600"
    >
      All
    </button>
    <button
      @click="setValue(1)"
      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
    >
      Pending
    </button>

    <button
      @click="setValue(2)"
      class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
    >
      In Progress
    </button>

    <button
      @click="setValue(3)"
      class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600"
    >
      Completed
    </button>
  </div>

  <p  class="mt-4 text-right text-gray-700">
    Showing <strong id="selectStatus">{{ statuses[selectedVal] }}</strong>
  </p>
      <!-- <p>This is a protected tasks page.</p> -->
    </div>

    <TaskTable       :status="selected"/>

    <QR id="qrSelf"></QR>

      </div>
  </template>