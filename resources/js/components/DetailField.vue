<template>
  <panel-item :field="field" >
     <audio-player slot="value" v-if="field.previewUrl" :src="field.previewUrl" />
  </panel-item>
</template>

<script>
export default {
   props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
