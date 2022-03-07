<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div v-if="value">
        <template v-if="isVideo">
          <video controls width="300">
            <source :src="value.url" :type="videoType" />
            Sorry, your browser doesn't support embedded videos.
          </video>
        </template>
        <template v-else-if="isImage">
          <a :href="value.url" target="_blank"
            ><img :src="value.url + '?w=300&h=300'" class="mb-2"
          /></a>
        </template>
      </div>

      <button
        @click.prevent="openKontainer"
        class="btn btn-default btn-primary inline-flex items-center relative"
        v-text="value ? 'Edit' : 'Browse'"
      >
        Browse
      </button>
      <button
        v-if="value"
        @click.prevent="remove"
        class="btn btn-default btn-primary inline-flex items-center relative"
      >
        Unlink
      </button>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  data() {
    return {
      token: null,
    };
  },

  computed: {
    isImage() {
      return this.value && this.value.type === "image";
    },
    isVideo() {
      return this.value && this.value.type === "video";
    },
    videoType() {
      if (!this.value) {
        return;
      }

      if (/\.mp4/i.test(this.value.url)) {
        return "video/mp4";
      }

      if (/\.webm/i.test(this.value.url)) {
        return "video/webm";
      }

      return "unknown";
    },
  },

  mounted() {
    this.token = this.makeid(32);

    window.addEventListener("message", this.receive, false);
  },

  destroyed() {
    window.removeEventListener("message", this.receive, false);
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value ? JSON.parse(this.field.value) : "";
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.value) || "");
    },

    openKontainer() {
      if (!this.field.kontainerUrl) {
        alert("Kontainer URL is missing");
        return;
      }

      // End the Kontainer URL with a trailing slash
      let url = this.field.kontainerUrl.replace(/\/?$/, "/");

      if (this.value && this.value.folderId) {
        url += "folder/" + this.value.folderId + "/";
      }

      if (this.value && this.value.fileId) {
        url += "file/" + this.value.fileId + "/";
      }

      url += "?cmsMode=1&cmsToken=" + this.token;

      window.open(
        url,
        "kontainer",
        "width=1024,height=768,popup,toolbar=no,location=no"
      );
    },

    receive(data) {
      if (!new RegExp("kontainer.com").test(data.origin)) {
        return;
      }

      let imageData = JSON.parse(data.data);

      if (!imageData) {
        alert("Error parsing image data");
        return;
      }

      if (imageData.token !== this.token) {
          return;
      }

      if (imageData.type !== "image" && imageData.type !== "video") {
        alert("Unknown type");
        return;
      }

      if (!imageData.url) {
        alert("Invalid URL");
        return;
      }

      this.value = imageData;
    },

    remove() {
      this.value = null;
    },

    makeid(length) {
      let result = "";
      let characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      let charactersLength = characters.length;

      for (let i = 0; i < length; i++) {
        result += characters.charAt(
          Math.floor(Math.random() * charactersLength)
        );
      }

      return result;
    },
  },
};
</script>
