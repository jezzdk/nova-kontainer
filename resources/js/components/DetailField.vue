<template>
  <panel-item :field="field">
    <template slot="value">
      <div v-if="field.value">
        <template v-if="isVideo">
          <video controls width="300">
            <source :src="field.value" :type="videoType" />
            Sorry, your browser doesn't support embedded videos.
          </video>
        </template>
        <template v-else-if="isImage">
          <a :href="field.value" target="_blank"><img :src="field.value + '?w=300&h=300'"></a>
        </template>
        <template v-else>
          <a :href="field.value" target="_blank">{{ field.value }}</a>
        </template>
      </div>
    </template>
  </panel-item>
</template>

<script>
export default {
  props: ["resource", "resourceName", "resourceId", "field"],

  computed: {
    isImage() {
      return /\.jpg|\.png|\.jpeg|\.gif$/i.test(this.field.value);
    },
    isVideo() {
      return /\.mp4|\.webm$/i.test(this.field.value);
    },
    videoType() {
      if (/\.mp4/i.test(this.field.value)) {
        return "video/mp4";
      }

      if (/\.webm/i.test(this.field.value)) {
        return "video/webm";
      }

      return "unknown";
    },
  },
};
</script>
