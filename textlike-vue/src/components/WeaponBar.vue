<script setup lang="ts">
import { useGameStore } from '../stores/gameStore'

const game = useGameStore()

function formatDurability(current: number, total: number): string {
  return `${current}/${total}`
}
</script>

<template>
  <div class="content-box weapon-bar" v-if="game.character">
    <table class="inventory-table">
      <!-- Equipped Weapon -->
      <tr v-if="game.equippedWeapon">
        <td class="item-name equipped">
          {{ game.equippedWeapon.name }}
          <span class="durability">
            ({{ formatDurability(game.equippedWeapon.currentDurability, game.equippedWeapon.totalDurability) }})
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.dropWeapon(game.equippedWeapon!.id)">Drop</a>
        </td>
      </tr>

      <!-- Other Weapons in Inventory -->
      <tr v-for="weapon in game.inventory.weapons.filter(w => w.id !== game.equippedWeapon?.id)" :key="weapon.id">
        <td class="item-name">
          {{ weapon.name }}
          <span class="durability">
            ({{ formatDurability(weapon.currentDurability, weapon.totalDurability) }})
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.equipWeapon(weapon.id)">Equip</a>
          &middot;
          <a @click="game.dropWeapon(weapon.id)">Drop</a>
        </td>
      </tr>

      <!-- Equipped Armor -->
      <tr v-if="game.equippedArmor">
        <td class="item-name equipped">
          {{ game.equippedArmor.name }}
          <span class="durability">
            ({{ formatDurability(game.equippedArmor.currentDurability, game.equippedArmor.totalDurability) }})
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.dropArmor(game.equippedArmor!.id)">Drop</a>
        </td>
      </tr>

      <!-- Other Armor in Inventory -->
      <tr v-for="armor in game.inventory.armors.filter(a => a.id !== game.equippedArmor?.id)" :key="armor.id">
        <td class="item-name">
          {{ armor.name }}
          <span class="durability">
            ({{ formatDurability(armor.currentDurability, armor.totalDurability) }})
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.equipArmor(armor.id)">Equip</a>
          &middot;
          <a @click="game.dropArmor(armor.id)">Drop</a>
        </td>
      </tr>

      <!-- Equipped Tome -->
      <tr v-if="game.equippedTome">
        <td class="item-name equipped">
          {{ game.equippedTome.name }}
          <span class="durability">
            ({{ formatDurability(game.equippedTome.currentDurability, game.equippedTome.totalDurability) }})
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.dropTome(game.equippedTome!.id)">Drop</a>
        </td>
      </tr>

      <!-- Other Tomes in Inventory -->
      <tr v-for="tome in game.inventory.tomes.filter(t => t.id !== game.equippedTome?.id)" :key="tome.id">
        <td class="item-name">
          {{ tome.name }}
          <span class="durability">
            ({{ formatDurability(tome.currentDurability, tome.totalDurability) }})
          </span>
        </td>
        <td class="item-actions">
          <a @click="game.equipTome(tome.id)">Equip</a>
          &middot;
          <a @click="game.dropTome(tome.id)">Drop</a>
        </td>
      </tr>

      <!-- Empty state -->
      <tr v-if="game.inventory.weapons.length === 0 && game.inventory.armors.length === 0 && game.inventory.tomes.length === 0">
        <td colspan="2" class="text-center">
          <em>No equipment</em>
        </td>
      </tr>
    </table>
  </div>
</template>

<style scoped>
.weapon-bar {
  margin-top: 10px;
}

.inventory-table {
  width: 100%;
}

.inventory-table td {
  padding: 4px;
}

.item-name {
  width: 70%;
}

.item-name.equipped {
  font-weight: bold;
}

.item-actions {
  text-align: right;
  white-space: nowrap;
}

.item-actions a {
  cursor: pointer;
}

.durability {
  font-size: 0.85em;
  color: #666;
}
</style>
