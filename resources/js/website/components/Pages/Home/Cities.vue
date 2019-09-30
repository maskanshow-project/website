<template>
	<section class="gallery-section py-5" v-if="is_exist(siteSetting.posters)">
		<div class="container">
			<div class="main_title">
				<h2> شهر های پرطرفدار </h2>
			</div>
			<div class="gallery">
				<div class="grid-sizer"></div>

                <div class="row rtl">

                    <div class="col-lg-6 col-md-12">
                        <a :href="siteSetting.posters[0].link" class="gallery-item grid-wide set-bg w-100" :class="{ 'height' : !Res }"
                            :style="{ backgroundImage : `url(${siteSetting.posters[0].image ? url + siteSetting.posters[0].image.large : '/img/default.jpg'})` }">
                            <div class="gi-info">
                                <h3> {{ siteSetting.posters[0].title }} </h3>
                                <p> {{ siteSetting.posters[0].description }} </p>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-6 col-md-12">

                        <div>
                            <a :href="siteSetting.posters[1].link" class="gallery-item grid-long set-bg w-100"
                            :style="{ backgroundImage : `url(${siteSetting.posters[1].image ? url + siteSetting.posters[1].image.large : '/img/default.jpg'})` }">
                                <div class="gi-info">
                                    <h3> {{ siteSetting.posters[1].title }} </h3>
                                    <p> {{ siteSetting.posters[1].description }} </p>
                                </div>
                            </a>
                        </div>

                        <div>
                            <a :href="siteSetting.posters[2].link" class="gallery-item set-bg w-48"
                                :style="{ backgroundImage : `url(${siteSetting.posters[2].image ? url + siteSetting.posters[2].image.large : '/img/default.jpg'})` }">
                                <div class="gi-info">
                                    <h3> {{ siteSetting.posters[2].title }} </h3>
                                    <p> {{ siteSetting.posters[2].description }} </p>
                                </div>
                            </a>
                            <a :href="siteSetting.posters[3].link" class="gallery-item set-bg w-48 float-right"
                                :style="{ backgroundImage : `url(${siteSetting.posters[3].image ? url + siteSetting.posters[3].image.large : '/img/default.jpg'})` }">
                                <div class="gi-info">
                                    <h3> {{ siteSetting.posters[3].title }} </h3>
                                    <p> {{ siteSetting.posters[3].description }} </p>
                                </div>
                            </a>
                        </div>

                    </div>

                </div>

				
			</div>
		</div>
	</section>
</template>

<script>

    import { mapState } from 'vuex';
    import mixin from '../../../mixin';

    export default {

        mixins : [mixin] ,

        computed : {
            ...mapState([
                'siteSetting' ,
                'url'
            ])
        }

    }
</script>

<style scoped>

    .height {
        height: calc(100% - 20px);
    }

    .w-48 {
        width: 48% !important
    }

    .gallery * , .gallery-item:before , .gallery-item:after {
        border-radius: 5px;
    }

    .gi-info h3 {
        border-radius: unset !important;
    }

</style>
