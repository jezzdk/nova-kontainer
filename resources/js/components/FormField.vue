<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <input
        :id="field.name"
        type="hidden"
        class="w-full form-control form-input form-input-bordered"
        :class="errorClasses"
        :placeholder="field.name"
        v-model="value"
      />
      <div v-if="value">
        <template v-if="isVideo">
          <video controls width="300">
            <source :src="value" :type="videoType" />
            Sorry, your browser doesn't support embedded videos.
          </video>
        </template>
        <template v-else-if="isImage">
          <a :href="value" target="_blank"
            ><img :src="value + '?w=300&h=300'" class="mb-2"
          /></a>
        </template>
      </div>

      <button
        @click.prevent="openKontainer"
        class="btn btn-default btn-primary inline-flex items-center relative"
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

  computed: {
    isImage() {
      return /\.jpg|\.png|\.jpeg|\.gif$/i.test(this.value);
    },
    isVideo() {
      return /\.mp4|\.webm$/i.test(this.value);
    },
    videoType() {
      if (/\.mp4/i.test(this.value)) {
        return "video/mp4";
      }

      if (/\.webm/i.test(this.value)) {
        return "video/webm";
      }

      return "unknown";
    },
  },

  mounted() {
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
      this.value = this.field.value || "";
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || "");
    },

    openKontainer() {
      if (!this.field.kontainerUrl) {
        alert("Kontainer URL is missing");
        return;
      }

      window.open(
        this.field.kontainerUrl + "?cmsMode=1",
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

      if (imageData.type !== "image" && imageData.type !== "video") {
        alert("Unknown type");
        return;
      }

      if (!imageData.url) {
        alert("Invalid URL");
        return;
      }

      this.value = imageData.url;
    },

    remove() {
      this.value = null;
    },
  },
};
</script>
