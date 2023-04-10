<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  props: {
    icon: {
      type: String,
      required: true,
      default: 'success'
    },
    title: {
      type: String,
      required: true
    },
    text: {
      type: String,
      required: true
    }
  },
  methods: {
    toastTopEnd() {
      this.$swal({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        icon: this.$props.icon,
        title: this.$props.title,
        text: this.$props.title
      })
    }
  }
})
</script>
<template>
  <input :value="title" type="hidden" @onchange="toastTopEnd()" />
</template>
