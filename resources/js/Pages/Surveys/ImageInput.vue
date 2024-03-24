<template>
    <div>
        <div class="max-w-3xl bg-white rounded-md shadow margin-bottom-3rem">
            <!-- scroll area -->
            <section class="h-full p-8 w-full h-full flex flex-col">
                <header
                    class="border-dashed border-2 border-gray-400 py-12 flex flex-col justify-center items-center"
                >
                    <p
                        class="mb-3 font-semibold text-gray-900 flex flex-wrap justify-center"
                    >
                        <span>{{ $t('Drag and drop your')}}</span>&nbsp;<span
                            >{{ $t('images anywhere or')}}</span
                        >
                    </p>
                    <input
                        id="hidden-input"
                        type="file"
                        ref="file"
                        @change="setFiles"
                        name="files[]"
                        multiple
                        class="hidden"
                    />
                    <button
                        @click="$refs.file.click()"
                        id="button"
                        class="mt-2 rounded-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 focus:shadow-outline focus:outline-none"
                    >
                        {{$t('Upload an image')}}
                    </button>
                </header>

                <h1 class="pt-8 font-semibold sm:text-lg text-gray-900">
                     {{ $t('To Upload') }}
                </h1>

                <ul
                    id="gallery"
                    class="flex overflow-auto flex-1 flex-wrap -m-1"
                >
                    <li
                        v-if="images.length === 0"
                        id="empty"
                        class="h-full w-full text-center flex flex-col items-center justify-center items-center"
                    >
                        <img
                            class="mx-auto w-32"
                            src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png"
                            alt="no data"
                        />
                        <span class="text-small text-gray-500"
                            >{{ $t('No Images selected')}}</span
                        >
                    </li>
                    <li
                        v-else
                        style="width: 100%"
                        class="flex flex-wrap p-1 h-24"
                    >
                        <article
                            style="width: 27%"
                            v-for="(image, index) in images"
                            :key="index"
                            :class="[index > 0 && index % 3 != 0 ? 'ml-4' : '']"
                            tabindex="0"
                            class="group mt-4 w-full h-full rounded-md focus:outline-none focus:shadow-outline elative bg-gray-100 cursor-pointer relative shadow-sm"
                        >
                            <img
                                alt="upload preview"
                                class="img-preview hidden w-full h-full sticky object-cover rounded-md bg-fixed"
                            />

                            <section
                                class="flex flex-col rounded-md text-xs break-words w-full h-full z-20 absolute top-0 py-2 px-3"
                            >
                                <img
                                    :src="image.base64code"
                                    :alt="image.name"
                                />
                                <div class="flex">
                                    <span class="p-1 text-blue-800">
                                        <i>
                                            <svg
                                                class="fill-current w-4 h-4 ml-auto pt-1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    d="M15 2v5h5v15h-16v-20h11zm1-2h-14v24h20v-18l-6-6z"
                                                />
                                            </svg>
                                        </i>
                                    </span>
                                    <p
                                        v-if="image.size"
                                        class="pt-2 size text-xs text-gray-700"
                                    >
                                        {{
                                            image.size > 1024
                                                ? image.size > 1048576
                                                    ? Math.round(
                                                          image.size / 1048576
                                                      ) + "mb"
                                                    : Math.round(
                                                          image.size / 1024
                                                      ) + "kb"
                                                : image.size + "b"
                                        }}
                                    </p>
                                    <button
                                        @click="removeImage(index)"
                                        class="delete ml-auto focus:outline-none hover:bg-gray-300 p-1 rounded-md text-gray-800"
                                    >
                                        <svg
                                            class="pointer-events-none fill-current w-4 h-4 ml-auto"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                class="pointer-events-none"
                                                d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </section>
                        </article>
                    </li>
                </ul>
            </section>
        </div>
        <br />
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
export default {
    components: {
        LoadingButton,
    },
    props: {
        update: {
            type: Boolean,
            default: false,
        },
    },
    emits: ["images"],
    data() {
        return {
            images: [],
        };
    },
    methods: {
        toBase64(image) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onloadend = () => {
                    resolve(reader.result);
                };
                reader.onerror = (err) => {
                    reject(err);
                };
                reader.readAsDataURL(image);
            });
        },
        removeImage(index) {
            this.images.splice(index, 1);
            this.$emit("images", this.images);
        },
        async setFiles(event) {
            const file = event.target.files;
            if (file.length === 0) {
                return false;
            }
            for (var i = 0; i < file.length; i++) {
                let getFile = file[i];
                try {
                    const base64code = await this.toBase64(getFile);
                    getFile.base64code = base64code;
                    this.images.push(getFile);
                    this.$emit("images", this.images);
                } catch (e) {
                    console.log(e);
                }
            }
        },
    },
};
</script>

<style></style>
