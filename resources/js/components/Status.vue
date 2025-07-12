<script setup>
import { ref } from 'vue'

const selected = ref(null)
const statuses = ['All', 'Pending', 'In-Progress', 'Completed']
let selectedVal = 0;


function setValue(val) {
  selected.value = val
  selectedVal = val
  console.log('Selected:', val)
}
</script>

<template>
  <div class="flex justify-end space-x-2">
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

  <p class="mt-4 text-right text-gray-700">
    Showing <strong>{{ statuses[selectedVal] }}</strong>
  </p>
</template>