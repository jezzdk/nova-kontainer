<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div v-if="value" class="mb-2">
        <template v-if="isVideo">
          <video controls width="300">
            <source :src="value.url" :type="videoType" />
            Sorry, your browser doesn't support embedded videos.
          </video>
        </template>
        <template v-else-if="isImage">
          <a :href="value.url" target="_blank">
            <img :src="value.url + '?w=300&h=300'" class="mb-2">
          </a>
        </template>
        <template v-else-if="isFile">
          <a :href="value.url" target="_blank">{{ value.url }}</a>
        </template>
      </div>

      <button
        :disabled="field.readonly"
        @click.prevent="openKontainer"
        class="btn btn-default btn-primary inline-flex items-center relative"
        v-text="value ? __('Edit') : __('Browse')"
      >
        {{ __('Browse') }}
      </button>
      <button
        v-if="value"
        :disabled="field.readonly"
        @click.prevent="remove"
        class="btn btn-default btn-primary inline-flex items-center relative"
      >
        {{ __('Unlink') }}
      </button>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      token: null,
      popupWidth: 1024,
      popupHeight: 768,
      popupTop: 0,
      popupLeft: 0,
    };
  },

  computed: {
    isImage() {
      return this.value && this.value.type === 'image';
    },
    isVideo() {
      return this.value && this.value.type === 'video';
    },
    isFile() {
      return this.value && ! ['image','video'].includes(this.value.type);
    },
    videoType() {
      if (!this.value) {
        return;
      }

      if (/\.mp4/i.test(this.value.url)) {
        return 'video/mp4';
      }

      if (/\.webm/i.test(this.value.url)) {
        return 'video/webm';
      }

      return 'unknown';
    },
  },

  mounted() {
    this.token = this.makeid(32);
    this.popupWidth = window.screen.width * 0.8;
    this.popupHeight = window.screen.height * 0.8;
    this.popupTop = (window.screen.height * 0.15) / 2;
    this.popupLeft = (window.screen.width * 0.2) / 2;

    window.addEventListener('message', this.receive, false);
  },

  destroyed() {
    window.removeEventListener('message', this.receive, false);
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value ? JSON.parse(this.field.value) : '';
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.value) || '');
    },

    openKontainer() {
      if (!this.field.kontainerUrl) {
        alert(this.__('Kontainer URL is missing'));
        return;
      }

      // End the Kontainer URL with a trailing slash
      let url = this.field.kontainerUrl.replace(/\/?$/, '/');

      if (this.value && (this.value.folderId || this.value.fileId)) {
        if (this.value.folderId) {
            url += 'folder/' + this.value.folderId + '/';
        }

        if (this.value.fileId) {
            url += 'file/' + this.value.fileId + '/';
        }
      } else {
        url += 'login-cms-redirect/';
      }

      url += '?cmsMode=1&cmsToken=' + this.token;

      window.open(
        url,
        'kontainer',
        'width='+this.popupWidth+',height='+this.popupHeight+',top='+this.popupTop+',left='+this.popupLeft+',popup'
      );
    },

    receive(data) {
      if (!this.field.kontainerUrl.includes(data.origin)) {
        return;
      }

      let imageData = JSON.parse(data.data);

      if (!imageData) {
        alert(this.__('Error parsing image data'));
        return;
      }

      if (!imageData.url) {
        alert(this.__('Invalid URL'));
        return;
      }

      if (imageData.token !== this.token) {
        return;
      }

      if (! ['image', 'video', 'file'].includes(imageData.type)) {
        alert(this.__('Unknown type'));
        return;
      }

      if (this.field.allowType === 'images' && imageData.type !== 'image') {
        alert(this.__('Only images allowed'));
        return;
      }

      if (this.field.allowType === 'videos' && imageData.type !== 'video') {
        alert(this.__('Only videos allowed'));
        return;
      }

      if (this.field.allowType === 'files' && imageData.type !== 'file') {
        alert(this.__('Only files allowed'));
        return;
      }

      this.value = imageData;
    },

    remove() {
      this.value = null;
    },

    makeid(length) {
      let result = '';
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
