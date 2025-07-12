<template>
    <div class="p-4">
      <h2 class="text-xl font-bold mb-4">📋 Task List</h2>
      <Quote></Quote>
  
      <table id="tasksTable" class="w-full border border-collapse">
        <thead>
          <tr class="bg-gray-100">
            <th class="headId border px-4 py-2">ID</th>
            <th class="headTitle border px-4 py-2">Title</th>
            <th class="headDesc border px-4 py-2">Description</th>
            <th class="headStatus border px-4 py-2">Status</th>
            <th class="headDeadline border px-4 py-2">Deadline</th>
            <th class="headActions border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in limitedTasks" :key="task.id" class="hover:bg-gray-50 taskRow {{ statuses[(task.status - 1)] }}">
            <!-- <tr v-for="task in tasks" :key="task.id" class="hover:bg-gray-50 taskRow {{ statuses[(task.status - 1)] }}"> -->
              <td class="rowId border px-4 py-2">{{ task.id }}</td>
            <td class="rowTitle border px-4 py-2">{{ task.title }}</td>
            <td class="rowDesc border px-4 py-2">{{ task.description }}</td>
            <td class="rowStatus border px-4 py-2">{{ statuses[(task.status - 1)] }}</td>
            <td class="rowDeadline border px-4 py-2">{{ task.deadline }}</td>
            <td class="rowActions border px-4 py-2 text-center space-x-2">
              <span  @click="openModal(task)"  title="Edit" class="cursor-pointer">✏️</span>
              <span @click="deleteTask(task.id)" title="Delete" class="cursor-pointer">🗑️</span>
            </td>
          </tr>
        </tbody>
      </table>
  
      <button  @click="openModal({ title: '', content: '', status: '', deadline: '' })" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        ➕ Add Task
      </button>
      <div class="pager" id="pager">
<button  class="pageButton" v-if="currentPage > 0" @click="prevPage" >Previous</button>
        <!-- <span v-for="pageNum in limitedTasks" class="pageNumber sel">{{ pageNum }}</span> -->
        <!---<span
        v-for="page in totalPages"
        :key="page"
        :class="{ sel: page === currentPage, pageNumber }"
        @click="goToPage(page)">
        {{ page }}
      </span>-->


      <span
        v-for="page in totalPages"
        :key="page"
        :class="['pageNumber',{ sel: page === currentPage+1 }]"
        :disabled = "page === currentPage+1"
        @click="goToPage(page-1)">
        {{ page }}
      </span>


      <!-- <span 
        v-for="page in totalPages"
        :key="page"
        :disable = "{page === currentPage+1}"
        :class="{ sel: page === currentPage }, pageNumber"
        @click="goToPage(page)">
        {{ page }}
      </span>-->
      <button class="pageButton"  v-if="currentPage + 1 < totalPages" @click="nextPage" >Next</button>
      </div>

    
    
      <TaskModal
      :task="editingTask"
      :visible="showModal"
      @close="fetchTasks"
      @task-added="onTaskAdded"
    />
    
    </div>


  </template>
  
  <script setup>
  import { ref, onMounted, defineProps, watch, computed } from 'vue'
  import axios from 'axios'
  import TaskModal from './TaskModal.vue'
  import Quote from './Quote.vue'
  const props = defineProps({
  status: 0,})


  const statuses = ['Pending', 'In-Progress', 'Completed']

  axios.defaults.withCredentials = true

  const tasks = ref([])
  let showModal = ref(false)
  const editingTask = ref(null)


  function openModal(task) {
  editingTask.value = task // Pass existing post
  showModal.value = true
  }

  const totalPages = computed(() =>
  Math.ceil(tasks.value.length / pageSize.value)
);
  
  const fetchTasks = async () => {
    try {
    // return;
    showModal = ref(false);
      const response = await axios.get('/api/tasks') // Adjust URL as needed
      tasks.value = response.data
    } catch (error) {
      console.error('Error fetching tasks:', error)
    }
  }
  

  const fetchTasksByStatus = async () => {
    try {
    // return;
      const response = await axios.get('/api/tasks-by-status/'+props.status) // Adjust URL as needed
      tasks.value = response.data
    } catch (error) {
      console.error('Error fetching tasks:', error)
    }
  }


  const currentPage = ref(0);
  const pageSize = ref(5);


  const limitedTasks = computed(() => {
  const start = currentPage.value * pageSize.value;
  return tasks.value.slice(start, start + pageSize.value);
});

function nextPage() {
  currentPage.value++;
}

function prevPage() {
  if (currentPage.value > 0) currentPage.value--;
}


function goToPage(page) {
  currentPage.value = page;
}

  const addTask = () => {
    alert('Add functionality goes here')
  }
  
  const editTask = (task) => {
    alert(`Edit task ID: ${task.id}`)
  }
  
  const deleteTask = async (id) => {
    if (confirm('Are you sure you want to delete this task?')) {
      try {
        await axios.delete(`/api/tasks/${id}`)
        tasks.value = tasks.value.filter(p => p.id !== id)
      } catch (error) {
        console.error('Delete failed:', error)
      }
    }
  }

  watch(
  () => props.status,
  () => {
    if(props.status>0)
    fetchTasksByStatus()
    else
    fetchTasks()
  },
  { immediate: true }
)

  
  onMounted(() => {
    fetchTasks()
  })
  </script>
  
  <style scoped>
  table {
    font-family: sans-serif;
  }


  body
  {
    margin: auto;
  }

  #tasksTable
{
  margin: 10px auto;

    border: 2px solid slateblue;
    /* width: 98vw; */
}

#tasksTable thead
{
  background-color: gray;
  padding: 2px;
}



.taskRow 
{
  padding: 3px;
  background-color: whitesmoke;
}

.taskRow:hover
{
  background-color: darkslategrey;
  color: ghostwhite;
}

td
{
  padding: 5px;
  text-align: center;
}

/*.In-Progress
{
  background-color: ;
}*/

  </style>