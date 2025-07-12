<!-- Quote.vue -->
<template>
    <div class="p-4 bg-gray-100 rounded shadow w-full max-w-xl mx-auto text-center">
      <p class="text-lg italic">"{{ quote.quote }}"</p>
      <!-- <p class="text-lg italic">"{{ quote.content }}"</p> -->
      <p class="mt-2 text-sm text-gray-600">— {{ quote.author }}</p>
  
      <button @click="getQuote" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        New Quote
      </button>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  // require("dotenv").config();
  
  const quote = ref({ quote: '', author: '' })
  
  const getQuote = async () => {
    console.log('hi')
    try {
      // console.log(process.env.BCRYPT_ROUNDS)
      
      const res = await axios.get('api/quotes')
      /*const res = await axios.get('https://api.api-ninjas.com/v1/quotes', {headers:{'X-Api-Key': import.meta.env.VITE_API_KEY,"Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "*",
        "Access-Control-Allow-Headers": "'Access-Control-Allow-Headers: Origin'"}})*/
     /* const res = await axios.get('https://api.api-ninjas.com/v1/quotes', {headers:{'X-Api-Key': import.meta.env.VITE_API_KEY}}).then(response => {
  console.log(response.data);
})
.catch(error => {
  console.error(error);
});*/
// quote.value = res.data
quote.value = res.data[0]
      console.log(res.data)
    } catch (err) {
      // console.log('gug: '+process.env.BCRYPT_ROUNDS)
      quote.value = { quote: 'Failed to load quote.', author: '' }
    }
    // console.log(import.meta.env.VITE_API_KEY)

  }
  
  onMounted(getQuote)
  </script>