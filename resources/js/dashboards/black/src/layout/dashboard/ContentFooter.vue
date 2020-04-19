<template>
  <footer class="footer" dir="rtl">
    <div class="container-fluid">
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link pull-left" title="+989158916793" href="telto:+9158916793">
            <i class="tim-icons icon-support-17"></i>
            تماس با پشتیبانی
          </a>

          <a class="nav-link pull-left ml-3" title="+989105009868" href="telto:+9105009868">
            <i class="tim-icons icon-laptop"></i>
            تماس با تیم توسعه دهنده
          </a>
        </li>
      </ul>
      <div class="copyright">
        © 2017 - {{year}} طراحی و توسعه <i class="tim-icons icon-heart-2"></i> توسط
        <a href="https://www.smaat.ir" target="_blank" rel="noopener">SmaaT Co</a>
      </div>
    </div>
  </footer>
</template>

<script>
  export default {
    data() {
      return {
        year: new Date().getFullYear()
      }
    },
    created() {
      setTimeout(() => {
        $('footer.footer').slideDown();
      }, 1000);
    }
  };
</script>

<style scoped>

footer {
  display: none;
}

</style>

