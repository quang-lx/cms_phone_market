<template>
  <div class="pdf-zoom">
    <a @click.prevent.stop="zoomIn" class="icon" :disabled="isDisabled">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M93.2 84.8L70.6 62.3c4.3-6 6.8-13.3 6.8-21.2C77.4 21 61.2 4.8 41.1 4.8 21 4.8 4.8 21 4.8 41.1S21 77.4 41.1 77.4c7.8 0 15.1-2.5 21-6.7l22.6 22.6c2 2 5.4 2 7.4 0l1.1-1.1c2-2 2-5.3 0-7.4zM41.1 66.6c-14.1 0-25.5-11.4-25.5-25.5S27 15.6 41.1 15.6 66.6 27 66.6 41.1 55.2 66.6 41.1 66.6z"></path><path d="M53.1 36.5h-7.6v-7.6c0-2.5-2-4.5-4.5-4.5s-4.5 2-4.5 4.5v7.6H29c-2.5 0-4.5 2-4.5 4.5s2 4.5 4.5 4.5h7.6v7.6c0 2.5 2 4.5 4.5 4.5s4.5-2 4.5-4.5v-7.6h7.6c2.5 0 4.5-2 4.5-4.5s-2.1-4.5-4.6-4.5z"></path></svg>
    </a>
    <a @click.prevent.stop="zoomOut" class="icon" :disabled="isDisabled">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M93.2 84.8L70.6 62.3c4.3-6 6.8-13.3 6.8-21.2C77.4 21 61.2 4.8 41.1 4.8 21 4.8 4.8 21 4.8 41.1S21 77.4 41.1 77.4c7.8 0 15.1-2.5 21-6.7l22.6 22.6c2 2 5.4 2 7.4 0l1.1-1.1c2-2 2-5.3 0-7.4zM41.1 66.6c-14.1 0-25.5-11.4-25.5-25.5S27 15.6 41.1 15.6 66.6 27 66.6 41.1 55.2 66.6 41.1 66.6z"></path><path d="M29 36.5c-2.5 0-4.5 2-4.5 4.5s2 4.5 4.5 4.5h24.2c2.5 0 4.5-2 4.5-4.5s-2-4.5-4.5-4.5H29z"></path></svg>
    </a>
    <a @click.prevent.stop="fitWidth" class="icon" :disabled="isDisabled"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M6 16H0v-6h2v4h4zM16 6h-2V2h-4V0h6zM1 16a.999.999 0 0 1-.707-1.707l5-5a.999.999 0 1 1 1.414 1.414l-5 5A.997.997 0 0 1 1 16zm9-9a.999.999 0 0 1-.707-1.707l5-5a.999.999 0 1 1 1.414 1.414l-5 5A.997.997 0 0 1 10 7z"></path></svg></a>
    <a @click.prevent.stop="fitAuto" class="icon" :disabled="isDisabled"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15 7H9V1h2v4h4zm-8 8H5v-4H1V9h6zm3-8a.999.999 0 0 1-.707-1.707l5-5a.999.999 0 1 1 1.414 1.414l-5 5A.997.997 0 0 1 10 7zm-9 9a.999.999 0 0 1-.707-1.707l5-5a.999.999 0 1 1 1.414 1.414l-5 5A.997.997 0 0 1 1 16z"></path></svg></a>
  </div>
</template>

<script>

export default {
  name: 'PDFZoom',



  props: {
    scale: {
      type: Number,
    },
    increment: {
      type: Number,
      default: 0.25,
    },
  },

  computed: {
    isDisabled() {
      return !this.scale;
    },
  },

  methods: {
    zoomIn() {
      this.updateScale(this.scale + this.increment);
    },

    zoomOut() {
      if (this.scale <= this.increment) return;
      this.updateScale(this.scale - this.increment);
    },

    updateScale(scale) {
      this.$emit('change', {scale});
    },

    fitWidth() {
      this.$emit('fit', 'width');
    },

    fitAuto() {
      this.$emit('fit', 'auto');
    },
  },
}
</script>

<style>
.pdf-zoom a {
  float: left;
  cursor: pointer;
  display: block;
  border: 1px #333 solid;
  background: white;
  color: #333;
  font-weight: bold;
  line-height: 1.5em;
  width: 1.5em;
  height: 1.5em;
  font-size: 1.5em;
}
</style>
