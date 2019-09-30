<template>

    <section class="pt-5">
        <div class="container">

            <section class="error-container">
                <span class="four"><span class="screen-reader-text">4</span></span>
                <span class="zero"><span class="screen-reader-text">0</span></span>
                <span class="four"><span class="screen-reader-text">4</span></span>
            </section>

            <div class="text-center">
                <h2>
                    !!! صفحه مورد نظر پیدا نشد 
                </h2>
                <h4 class="mt-3">
                    <router-link to="/"> رفتن به صفحه اصلی </router-link>
                </h4>
            </div>

        </div>
    </section>

</template>

<script>
    export default {
        metaInfo() {
            return {
                title : '404' ,
            }
        } ,
    }
</script>