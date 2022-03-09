<template>
    <div v-if="fileData">
      <template v-if="isImage">
        <img :src="fileData.url + '?w=200&h=50'">
      </template>
      <template v-else>
        {{ fileData.url }}
      </template>
    </div>
</template>

<script>
export default {
  props: ["resourceName", "field"],

  data () {
    return {
      fileData: null
    };
  },

  computed: {
    isImage() {
      return this.fileData && this.fileData.type === "image";
    },
  },

  mounted () {
      if (this.field.value) {
        this.fileData = JSON.parse(this.field.value);
      }
  }
};
</script>
