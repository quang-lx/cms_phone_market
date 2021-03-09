<template>
  <div class="pdf-viewer">
    <header class="pdf-viewer__header box-shadow">
      <div class="pdf-preview-toggle">
        <a @click.prevent.stop="togglePreview" class="icon">
          <svg data-v-15a853a4="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M31 14H13a1 1 0 0 1 0-2h18a1 1 0 1 1 0 2zM31 17H13a1 1 0 0 1 0-2h18a1 1 0 1 1 0 2zM26 20H13a1 1 0 1 1 0-2h13a1 1 0 1 1 0 2zM31 25H13a1 1 0 1 1 0-2h18a1 1 0 1 1 0 2zM31 28H13a1 1 0 1 1 0-2h18a1 1 0 1 1 0 2zM17 31h-4a1 1 0 1 1 0-2h4a1 1 0 1 1 0 2zM31 3H13a1 1 0 0 1 0-2h18a1 1 0 1 1 0 2zM31 6H13a1 1 0 0 1 0-2h18a1 1 0 1 1 0 2zM22 9h-9a1 1 0 0 1 0-2h9a1 1 0 1 1 0 2zM7.504 10H2.496A2.5 2.5 0 0 1 0 7.504V2.496A2.5 2.5 0 0 1 2.496 0h5.008A2.5 2.5 0 0 1 10 2.496v5.008A2.5 2.5 0 0 1 7.504 10zM2.496 2A.497.497 0 0 0 2 2.496v5.008c0 .273.223.496.496.496h5.008A.497.497 0 0 0 8 7.504V2.496A.497.497 0 0 0 7.504 2H2.496zM7.504 21H2.496A2.5 2.5 0 0 1 0 18.504v-5.008A2.5 2.5 0 0 1 2.496 11h5.008A2.5 2.5 0 0 1 10 13.496v5.008A2.5 2.5 0 0 1 7.504 21zm-5.008-8a.497.497 0 0 0-.496.496v5.008c0 .273.223.496.496.496h5.008A.497.497 0 0 0 8 18.504v-5.008A.497.497 0 0 0 7.504 13H2.496zM7.504 32H2.496A2.5 2.5 0 0 1 0 29.504v-5.008A2.5 2.5 0 0 1 2.496 22h5.008A2.5 2.5 0 0 1 10 24.496v5.008A2.5 2.5 0 0 1 7.504 32zm-5.008-8a.497.497 0 0 0-.496.496v5.008c0 .273.223.496.496.496h5.008A.497.497 0 0 0 8 29.504v-5.008A.497.497 0 0 0 7.504 24H2.496z"></path></svg>
        </a>
      </div>

      <PDFZoom
              :scale="scale"
              @change="updateScale"
              @fit="updateFit"
              class="header-item"
      />

      <PDFPaginator
              v-model="currentPage"
              :pageCount="pageCount"
              class="header-item"
      />

      <slot name="header"></slot>
    </header>

    <PDFData
            class="pdf-viewer__main"
            :url="url"
            @page-count="updatePageCount"
            @page-focus="updateCurrentPage"
            @document-rendered="onDocumentRendered"
            @document-errored="onDocumentErrored"
    >
      <template v-slot:preview="{pages}">
        <PDFPreview
                v-show="isPreviewEnabled"
                class="pdf-viewer__preview"
                v-bind="{pages, scale, currentPage, pageCount, isPreviewEnabled}"
        />
      </template>

      <template v-slot:document="{pages}">
        <PDFDocument
                class="pdf-viewer__document"
                :class="{ 'preview-enabled': isPreviewEnabled }"
                v-bind="{pages, scale, optimalScale, fit, currentPage, pageCount, isPreviewEnabled}"
                @scale-change="updateScale"
        />
      </template>
    </PDFData>
  </div>
</template>

<script>

    import PDFDocument from './PDFDocument';
    import PDFData from './PDFData';
    import PDFPaginator from './PDFPaginator';
    import PDFPreview from './PDFPreview';
    import PDFZoom from './PDFZoom';

    function floor(value, precision) {
        const multiplier = Math.pow(10, precision || 0);
        return Math.floor(value * multiplier) / multiplier;
    }

    export default {
        name: 'PDFViewer',

        components: {
            PDFDocument,
            PDFData,
            PDFPaginator,
            PDFPreview,
            PDFZoom,

        },

        props: {
            url: String,
        },

        data() {
            return {
                scale: undefined,
                optimalScale: undefined,
                fit: undefined,
                currentPage: 1,
                pageCount: undefined,
                isPreviewEnabled: false,
            };
        },

        methods: {
            onDocumentRendered() {
                this.$emit('document-errored', this.url);
            },

            onDocumentErrored(e) {
                this.$emit('document-errored', e);
            },

            updateScale({scale, isOptimal = false}) {
                const roundedScale = floor(scale, 2);
                if (isOptimal) this.optimalScale = roundedScale;
                this.scale = roundedScale;
            },

            updateFit(fit) {
                this.fit = fit;
            },

            updatePageCount(pageCount) {
                this.pageCount = pageCount;
            },

            updateCurrentPage(pageNumber) {
                this.currentPage = pageNumber;
            },

            togglePreview() {
                this.isPreviewEnabled = !this.isPreviewEnabled;
            },
        },

        watch: {
            url() {
                this.currentPage = undefined;
            },
        },

        mounted() {
            document.body.classList.add('overflow-hidden');
        },
    };
</script>

<style scoped>
  header {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    padding: 1em;
    position: relative;
    z-index: 99;
  }
  .header-item {
    margin: 0 2.5em;
  }

  .pdf-viewer .pdf-viewer__document,
  .pdf-viewer .pdf-viewer__preview {
    top: 70px;
  }

  .pdf-viewer__preview {
    display: block;
    width: 15%;
    right: 85%;
  }

  .pdf-viewer__document {
    top: 70px;
    width: 100%;
    left: 0;
  }

  .pdf-viewer__document.preview-enabled {
    width: 85%;
    left: 15%;
  }

  @media print {
    header {
      display: none;
    }
  }
</style>
