<script setup lang="ts">
import { ref } from 'vue'
import { useGameStore } from '../stores/gameStore'

const game = useGameStore()
const characterName = ref('')
const showNewGame = ref(false)

function startGame() {
  if (characterName.value.trim()) {
    game.startNewGame(characterName.value.trim())
  }
}

function continueGame() {
  game.loadGame()
}
</script>

<template>
  <div class="main-menu">
    <div class="content-box text-center">
      <img src="/logo.png" class="logo" alt="Textlike" />
      <p class="room-desc">
        Textlike is a roguelike you can play in any web browser. Explore randomly
        generated dungeons, fight enemies, collect loot, and try to survive as long
        as you can.
      </p>
    </div>

    <div class="content-box" v-if="!showNewGame">
      <h3>Play</h3>
      <div class="text-center">
        <p>
          <button @click="showNewGame = true">New Game</button>
        </p>
        <p v-if="game.highScores.length > 0">
          <button @click="continueGame" :disabled="!game.character">
            Continue
          </button>
        </p>
      </div>
    </div>

    <div class="content-box" v-else>
      <h3>New Character</h3>
      <form @submit.prevent="startGame" class="text-center">
        <p>
          <input
            type="text"
            v-model="characterName"
            placeholder="Enter character name"
            maxlength="20"
            autofocus
          />
        </p>
        <p>
          <button type="submit" :disabled="!characterName.trim()">
            Start Adventure
          </button>
          <button type="button" @click="showNewGame = false" class="ml-1">
            Cancel
          </button>
        </p>
      </form>
    </div>

    <div class="content-box" v-if="game.highScores.length > 0">
      <h3>High Scores</h3>
      <ol>
        <li v-for="score in game.highScores" :key="score.id">
          {{ score.score }} - {{ score.characterName }} (Lvl {{ score.level }}, Floor {{ score.floor }})
        </li>
      </ol>
    </div>
  </div>
</template>

<style scoped>
.main-menu {
  padding-top: 20px;
}

.ml-1 {
  margin-left: 10px;
}
</style>
