<template>
  <panel-item :field="field">
    <template slot="value">
      <div v-if="fileData">
        <template v-if="isVideo">
          <video controls width="300">
            <source :src="fileData.url" :type="videoType" />
            Sorry, your browser doesn't support embedded videos.
          </video>
        </template>
        <template v-else-if="isImage">
          <a :href="fileData.url" target="_blank"><img :src="fileData.url + '?w=300&h=300'"></a>
        </template>
        <template v-else>
          <a :href="fileData.url" target="_blank">{{ fileData.url }}</a>
        </template>
      </div>
    </template>
  </panel-item>
</template>

<script>
export default {
  props: ["resource", "resourceName", "resourceId", "field"],

  data () {
    return {
      fileData: null
    };
  },

  computed: {
    isImage() {
      return this.fileData && this.fileData.type === "image";
    },
    isVideo() {
      return this.fileData && this.fileData.type === "video";
    },
    videoType() {
      if (!this.fileData) {
        return;
      }

      if (/\.mp4/i.test(this.fileData.url)) {
        return "video/mp4";
      }

      if (/\.webm/i.test(this.fileData.url)) {
        return "video/webm";
      }

      return "unknown";
    },
  },

  mounted () {
      if (this.field.value) {
        this.fileData = JSON.parse(this.field.value);
      }
  }
};
</script>
