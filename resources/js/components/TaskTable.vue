<template>
    <div class="p-4">
      <!-- <h2 class="text-xl font-bold mb-4">📋 Task List</h2> -->
  
      <table id="tasksTable" class="w-full border border-collapse">
        <thead>
          <tr class="bg-gray-100">
            <th class="headId border px-4 py-2">#</th>
            <!-- <th  @click="sortKey = 'id', sortOrder=false" class="headId border px-4 py-2">ID</th> -->
            <!-- <th  @click="sortKey = 'id', sortOrder=!sortOrder" class="headId border px-4 py-2">ID</th> -->
            <!-- <th  @click="sortKey = 'id', !sortOrder" class="headId border px-4 py-2">ID</th> -->
            <th  @click="sorter('id')" class="headId border px-4 py-2" :class="[{ asc: sortOrder === true && sortKey === 'id' }, { desc: sortOrder === false && sortKey === 'id' }]">ID <span v-if="sortKey !== 'id'">↕</span><span v-else-if="!sortOrder">↓</span><span v-else>↑</span></th>
            <th  @click="sorter('title')" class="headTitle border px-4 py-2"  :class="[{ asc: sortOrder === true && sortKey === 'title' }, { desc: sortOrder === false && sortKey === 'title' }]">Title <span v-if="sortKey !== 'title'">↕</span><span v-else-if="!sortOrder">↓</span><span v-else>↑</span></th>
            <th  class="headDesc border px-4 py-2">Description</th>
            <th  @click="sorter('status')" class="headStatus border px-4 py-2" :class="[{ asc: sortOrder === true && sortKey === 'status' }, { desc: sortOrder === false && sortKey === 'status' }]">Status <span v-if="sortKey !== 'status'">↕</span><span v-else-if="!sortOrder">↓</span><span v-else>↑</span></th>
            <th  @click="sorter('deadline')" class="headDeadline border px-4 py-2" :class="[{ asc: sortOrder === true && sortKey === 'deadline' }, { desc: sortOrder === false && sortKey === 'deadline' }]">Deadline <span v-if="sortKey !== 'deadline'">↕</span><span v-else-if="!sortOrder">↓</span><span v-else>↑</span></th>
            <th class="headActions border px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(task, index) in limitedTasks" :key="task.id" class="hover:bg-gray-50 taskRow {{ statuses[(task.status - 1)] }}">
            <!-- <tr v-for="task in tasks" :key="task.id" class="hover:bg-gray-50 taskRow {{ statuses[(task.status - 1)] }}"> -->
              <td class="rowId border px-4 py-2">{{ (currentPage)*pageSize + index + 1 }}</td>
              <td class="rowId border px-4 py-2">{{ task.id }}</td>
            <td class="rowTitle border px-4 py-2">{{ task.title }}</td>
            <td class="rowDesc border px-4 py-2">{{ task.description }}</td>
            <td class="rowStatus border px-4 py-2">{{ statuses[(task.status - 1)] }}</td>
            <td class="rowDeadline border px-4 py-2">{{ ddmmyyyyPipe(task.deadline) }}</td>
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
<button  class="pageButton nonNumber" v-if="currentPage > 0" @click="prevPage" ><</button>
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
        :class="['pageNumber', 'pageButton',{ sel: page === currentPage+1 }]"
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
      <button class="pageButton nonNumber"  v-if="currentPage + 1 < totalPages" @click="nextPage" >></button>
      </div>
      <button class="exportExcel" @click="exportToExcel">Export to Excel</button>

    
    
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
  import ExcelJS from 'exceljs';


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

  /*function ddmmyyyyPipe(date)
  {
    return date.split('-')[2].split('T')[0]+ '-' +date.split('-')[1]+ '-' + date.split('-')[0]
  }*/

  function ddmmyyyyPipe(date)
  {
    if (!date|| date.length<6 )
    // if (date.length<6 || !date)
      return ''
    return date.split('-')[2].split('T')[0]+ '-' +date.split('-')[1]+ '-' + date.split('-')[0]
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
    console.log('loaded... fetching')
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


  const sortKey = ref('id'); // default sort key
// const sortOrder = ref('asc'); // default order
const sortOrder = ref(true); // default order, true for ascending
// const watchKey = ref(sortKey)

  /*const limitedTasks = computed(() => {
  const start = currentPage.value * pageSize.value;
  return tasks.value.slice(start, start + pageSize.value);
});*/

function sorter(key)
{
  if (sortKey.value === key)
    sortOrder.value = !sortOrder.value
  else
    sortOrder.value = true
  sortKey.value = key
}

/*function rootSort(sorted)
{
  tasks.value = sorted
}*/

const limitedTasks = computed(() => {
  const sorted = tasks.value.slice().sort((a, b) => {
    let aValue = a[sortKey.value];
    let bValue = b[sortKey.value];

    // If sorting by date, parse to Date
    if (sortKey.value === 'created_at') {
      aValue = new Date(aValue);
      bValue = new Date(bValue);
    }

    let result = 0;

    if (typeof aValue === 'string') {
      result = aValue.localeCompare(bValue);
    } else {
      result = aValue - bValue;
    }

    // return sortOrder.value === 'asc' ? result : -result;
    // return sortOrder.value === true ? result : -result;
    // return sortOrder.value === sortOrder ? result : -result;
    // return sortOrder ? result : -result;
    // return !sortOrder ? result : -result;

    // sortOrder = !sortOrder
    // sortOrder.val = !sortOrder
    // sortOrder.value = !sortOrder.value
    // console.log(sortOrder)
    // return sortOrder ? result : -result;
    return sortOrder.value ? result : -result;


  });
  console.log(sortOrder)

  const start = currentPage.value * pageSize.value;
  // tasks.value = sorted
  // rootSort(sorted)
  return sorted.slice(start, start + pageSize.value);
});

async function exportToExcel() {
  // 1. Create a new workbook
  const workbook = new ExcelJS.Workbook();
  const worksheet = workbook.addWorksheet('Tasks');


  const firstTask = tasks.value[0];
  console.log(firstTask)
  let headers = Object.keys(firstTask).map(key => ({
    /*header: key.charAt(0).toUpperCase() + key.slice(1), // capitalize
  key,
  width: 20*/

  name: key.charAt(0).toUpperCase() + key.slice(1)
}));
console.log(headers)
let sno = 0;
/*worksheet.columns = [  { header: '#', key: 'Sno', width: 10 }, ...headers]
let sno = 0;
// Object.entries(tasks).forEach(task => {
tasks.value.forEach(task => {
  // Pick only selected fields dynamically:
  console.log("task:\n")
  console.log(task)
  // const row = {'Sno': ++sno};
  const row = {};
  row['Sno'] = ++sno
  Object.keys(task).forEach(key => {
    // firstTask.forEach(key => {
    if  (key === 'status')
      row[key] = task[key]>2?"Completed":task[key]<2?"Pending":"In-Progress";
    else
      row[key] = task[key];
  });
  worksheet.addRow(row);
});

worksheet.getRow(1).eachCell(cell => {
  cell.font = { bold: true, color: { argb: 'FF0000' } };
});*/


worksheet.addTable({
    name: 'TaskTable',
    ref: 'A1',
    headerRow: true,
    style: {
      theme: 'TableStyleMedium2',
      showRowStripes: true
    },
    columns: [
      { name: '#' },
      ...headers
    ],
    // rows: tasks.map(task => [++sno, ...task])
    // rows: tasks.value.map(task => [++sno, ...task])
    // rows: tasks.value.map(task => [++sno, ...Object.values(task)])
    /*rows: tasks.value.map(task => {
      let row =[++sno]
      //row.push(Object.keys(task).map(key => {
        row.concat(Object.keys(task).map(key => {
        if(key === 'status')
          return row[key] = task[key]>2?"Completed":task[key]<2?"Pending":"In-Progress";
        console.log(row[key])
        // return row[key]
        return task[key]
      }))
      console.log(row)
      return row
    })*/
    rows: tasks.value.map(task => {
      let row =[++sno]
        row.push(...Object.keys(task).map(key => {
        if(key === 'status')
          return task[key]>2?"Completed":task[key]<2?"Pending":"In-Progress";
        return task[key]
      }))
      return row
    })
  });





  // 2. Add column headers
  /*  worksheet.columns = [
  { header: '#', key: 'id', width: 10 },
  { header: 'ID', key: 'id', width: 10 },
  { header: 'Title', key: 'title', width: 30 },
  { header: 'Description', key: 'description', width: 50 },
    { header: 'Status', key: 'status', width: 15 },
  ];
*/
  // 3. Add rows
  /*tasks.forEach(task => {
    worksheet.addRow(task);
  });*/

  // 4. Create a Blob and trigger download
  const buffer = await workbook.xlsx.writeBuffer();

  const blob = new Blob([buffer], {
    type:
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  });

  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'tasks.xlsx';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

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