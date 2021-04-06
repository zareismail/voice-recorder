<template>
  <audio-player slot="value" v-if="field.previewUrl" :src="field.previewUrl" />
</template>

<script>
export default {
   props: ['resourceName', 'field'],
}
</script>
