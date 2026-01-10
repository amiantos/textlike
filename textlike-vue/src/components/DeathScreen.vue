<script setup lang="ts">
import { useGameStore } from '../stores/gameStore'

const game = useGameStore()
</script>

<template>
  <div class="content-box death-screen" v-if="game.character">
    <h1>You Died</h1>
    <h2>Final Score: {{ game.score }}</h2>
    <p class="text-center">
      <a @click="game.submitScore()">Submit Score and Start Over</a>
    </p>
  </div>
</template>

<style scoped>
.death-screen {
  animation: fadeIn 1s ease-in;
}

.death-screen h1 {
  color: #660000;
}

.death-screen a {
  cursor: pointer;
}
</style>
