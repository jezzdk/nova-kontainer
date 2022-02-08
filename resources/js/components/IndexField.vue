<template>
    <div v-if="field.value">
      <template v-if="isImage">
        <img :src="field.value + '?w=200&h=50'">
      </template>
      <template v-else>
        {{ field.value }}
      </template>
    </div>
</template>

<script>
export default {
  props: ["resourceName", "field"],

  computed: {
    isImage() {
      return /\.jpg|\.png|\.jpeg|\.gif$/i.test(this.field.value);
    },
  },
};
</script>
