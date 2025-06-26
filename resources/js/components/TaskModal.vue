<template>
    <div
      v-if="visible"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <h2 class="text-lg font-bold mb-4">➕ Add New Task</h2>
  
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Title</label>
            <input
              v-model="form.title"
              type="text"
              class="w-full border border-gray-300 rounded px-3 py-2"
              required
            />
          </div>
  
          <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Description</label>
            <textarea
              v-model="form.description"
              class="w-full border border-gray-300 rounded px-3 py-2"
              required
            ></textarea>
          </div>

<div class="mb-4">
          <label class="block mb-2 font-medium">Status</label>
<select
  v-model="form.status"
  class="w-full border border-gray-300 p-2 rounded"
>
  <option disabled value="">Select status</option>
  <option
    v-for="option in statusOptions"
    :key="option.value"
    :value="option.value"
  >
    {{ option.label }}
  </option>
</select></div>
  
          <div class="flex justify-end gap-2">
            <button
              type="button"
              @click="close"
              class="px-4 py-2 bg-gray-300 text-black rounded"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { reactive, watch, defineEmits, defineProps } from 'vue'
  import axios from 'axios'
  axios.defaults.withCredentials = true


  const statusOptions = [
  { label: 'Pending', value: 1 },
  { label: 'In-Progress', value: 2 },
  { label: 'Completed', value: 3 }];

  const props = defineProps({
  task: {
    type: Object,
    default: () => ({
      title: '',
      description: '',
      status: ''
    })
  },
  visible: Boolean
})
  
  const emit = defineEmits(['close', 'task-added'])
  // const props = defineProps({ visible: Boolean })
  
  const form = reactive({
    title: '',
    description: '',
    status: 1
  })


  // Sync incoming prop into local form (when modal opens)
watch(
  () => props.task,
  (newTask) => {
    form.title = newTask?.title || ''
    form.description = newTask?.description || ''
    form.status = newTask?.status || 1
  },
  { immediate: true }
)
  
  const close = () => {
    emit('close')
    form.title = ''
    form.description = ''
    form.status = 1
    props.task = null
    // props.task = ref(null)
  }
  
  const submitForm = async () => {
    try {
    let response;
    console.log(props.task)
    // if (props.task===null)
    if (props.task.status==='')
      // if (props.task!=null)
      response = await axios.post('/api/tasks', { ...form })
    else
      response = await axios.patch('/api/tasks/'+props.task.id, { id: props.task.id, ...form })
    emit('task-added', response.data)
      close()
    } catch (error) {
      console.error('Failed to add task:', error)
      alert('Error adding task.')
    }
  }
  </script>