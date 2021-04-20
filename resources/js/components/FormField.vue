<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <span v-if="value" class="flex flex-wrap">
        <audio-player :src="field.previewUrl" :record="record" />
        <div class="py-4 px-2"> 
          <DeleteButton
            :dusk="field.attribute + '-internal-delete-link'"
            class="ml-auto text-danger"
            v-if="shouldShowRemoveButton"
            @click="confirmRemoval"
          /> 
        </div>
      </span>
      

      <audio-recorder
        v-else
        upload-url=''
        :attempts="1"
        :time="2"
        :after-recording="handleAudio" 
        :show-upload-button="false"
      />
    </template>

    <portal to="modals">
      <confirm-upload-removal-modal
        v-if="removeModalOpen"
        @confirm="removeFile"
        @close="closeRemoveModal"
      />
    </portal>
  </default-field>
</template>

<script> 
import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova'
import DeleteButton from './DeleteButton.vue'

export default {
  mixins: [FormField, HandlesValidationErrors],   

  components: {DeleteButton},

  props: ['resourceName', 'resourceId', 'field'], 

  data: () => ({
    record: {},
    removeModalOpen: false,
  }),

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || ''
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) { 
      formData.append(this.field.attribute, this.value || '')
    },

    handleAudio(record) { 
      this.record = record
      this.value = this.record.blob 
    }, 

    /**
     * Confirm removal of the linked file
     */
    confirmRemoval() {
      this.removeModalOpen = true
    },

    /**
     * Close the upload removal modal
     */
    closeRemoveModal() {
      this.removeModalOpen = false
    },

    /**
     * Remove the linked file from storage
     */
    async removeFile() {
      this.uploadErrors = new Errors()

      const {
        resourceName,
        resourceId,
        relatedResourceName,
        relatedResourceId,
        viaRelationship,
      } = this
      const attribute = this.field.attribute

      const uri =
        this.viaRelationship &&
        this.relatedResourceName &&
        this.relatedResourceId
          ? `/nova-api/${resourceName}/${resourceId}/${relatedResourceName}/${relatedResourceId}/field/${attribute}?viaRelationship=${viaRelationship}`
          : `/nova-api/${resourceName}/${resourceId}/field/${attribute}`

      try {
        await Nova.request().delete(uri)
        this.closeRemoveModal()
        this.deleted = true
        this.$emit('file-deleted')
        this.value = ''
        this.field.value = ''
        this.field.previewUrl = null
        Nova.success(this.__('The file was deleted!'))
      } catch (error) {
        this.closeRemoveModal()

        if (error.response.status == 422) {
          this.uploadErrors = new Errors(error.response.data.errors)
        }
      }
    },

    /**
     * Determine whether the field should show the remove button.
     */
    shouldShowRemoveButton() {
      return Boolean(this.field.deletable && !this.isReadonly)
    },
  },
}
</script>
