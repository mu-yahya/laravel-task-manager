<!-- Quote.vue -->
<template>
    <div class="p-4 bg-gray-100 rounded shadow w-full max-w-xl mx-auto text-center">
      <p class="text-lg italic">"{{ quote.content }}"</p>
      <p class="mt-2 text-sm text-gray-600">— {{ quote.author }}</p>
  
      <button @click="getQuote" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        New Quote
      </button>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  
  const quote = ref({ content: '', author: '' })
  
  const getQuote = async () => {
    try {
      const res = await axios.get('https://api.quotable.io/random')
      quote.value = res.data
    } catch (err) {
      quote.value = { content: 'Failed to load quote.', author: '' }
    }
  }
  
  onMounted(getQuote)
  </script>