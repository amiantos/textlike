<script setup lang="ts">
import { computed } from 'vue'
import { useGameStore } from '../stores/gameStore'
import { calculateMobBleeding } from '../engine/mobs'

const game = useGameStore()

const mobBleeding = computed(() => {
  if (!game.currentMob) return 0
  return calculateMobBleeding(game.currentMob)
})
</script>

<template>
  <div v-if="game.currentMob">
    <!-- Enemy Info Panel -->
    <div class="content-box enemy-info">
      <table>
        <tr>
          <td class="enemy-name">
            <span :class="{ 'name-small': game.currentMob.name.length > 20 }">
              {{ game.currentMob.name }}
            </span>
          </td>
          <td class="stat-cell">
            <span class="stat-label">Level:</span> {{ game.currentMob.level }}
          </td>
          <td class="stat-cell">
            <span class="stat-label">Health:</span>
            {{ game.currentMob.currentHealth }}
            <span v-if="mobBleeding > 0" class="bleeding">({{ mobBleeding }})</span>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <div class="enemy-stats">
              <span>
                <span class="stat-label">Resist:</span> {{ game.currentMob.resistance }}
              </span>
              <span>
                <span class="stat-label">Weak:</span> {{ game.currentMob.weakness }}
              </span>
              <span>
                <span class="stat-label">Str:</span> {{ game.currentMob.strength }}
              </span>
              <span>
                <span class="stat-label">Sta:</span> {{ game.currentMob.stamina }}
              </span>
              <span>
                <span class="stat-label">Dex:</span> {{ game.currentMob.dexterity }}
              </span>
            </div>
          </td>
        </tr>
      </table>
    </div>

    <!-- Battle Log -->
    <div class="content-box battle-log">
      <div
        v-for="entry in game.battleLog"
        :key="entry.id"
        class="battle-log-entry"
        :class="entry.type"
      >
        {{ entry.message }}
      </div>
      <div v-if="game.battleLog.length === 0" class="battle-log-entry">
        Combat has begun!
      </div>
    </div>
  </div>
</template>

<style scoped>
.enemy-info table {
  width: 100%;
}

.enemy-name {
  text-align: center;
  padding: 4px;
  border-bottom: 1px solid #808080;
}

.name-small {
  font-size: 0.85em;
}

.stat-cell {
  text-align: center;
  padding: 4px;
  border-bottom: 1px solid #808080;
  border-left: 1px solid #808080;
}

.enemy-stats {
  display: flex;
  justify-content: space-around;
  padding: 4px;
}

.enemy-stats span {
  padding: 0 4px;
  border-left: 1px solid #808080;
}

.enemy-stats span:first-child {
  border-left: none;
}

.battle-log-entry {
  padding: 4px 0;
  border-bottom: 1px solid #eee;
}

.battle-log-entry:last-child {
  border-bottom: none;
}

.battle-log-entry.attack {
  color: #006600;
}

.battle-log-entry.mobAttack {
  color: #660000;
}

.battle-log-entry.bleeding {
  color: #990000;
}

.battle-log-entry.death {
  font-weight: bold;
}
</style>
