<template>
    <FilterContainer>
        <div class="flex justify-between">
            <span>{{ filter.name }}</span>
            <div v-if="filter.withAnyTags != 'never'" class="flex items-center">
                <input id="default-checkbox" type="checkbox" value="" v-model="useAny" @change="handleAny(true)"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="default-checkbox"
                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 px-1">Any</label>
            </div>
        </div>

        <template #filter>
            <!-- <SelectControl
        :dusk="`${filter.name}-select-filter`"
        label="label"
        class="w-full block"
        size="sm"
        @change="handleChange"
        :value="value"
        :options="filter.options"
      >
        <option value="" :selected="value === ''">&mdash;</option>
      </SelectControl> -->
            <!-- <multiselect :clear-on-select="true" :close-on-select="true" :dusk="filter.name + '-filter-select'"
                    :internal-search="false" :limit-text="limitText" :limit="3" :loading="isLoading" :max-height="600"
                    :mode="true" :options-limit="300" :options="filter.options" :preserve-search="true"
                    :searchable="true" :show-no-results="false" :hide-selected="true" @input="handleChange"
                    @search-change="asyncFind" @select="select" label="name" open-direction="bottom"
                    :placeholder="placeholder" :noOptions="noOptions" track-by="name" v-model="value"></multiselect> -->
            <multiselect :dusk="filter.name + '-filter-select'" :options="filter.options" v-model="value"
                :placeholder="placeholder" :loading="isLoading" mode="tags" :infinite="true" :searchable="true"
                track-by="name" value-prop="name" label="name" :limit="300" :clear-on-select="true" :close-on-select="true"
                @input="handleChange" @search-change="asyncFind" @select="select">
            </multiselect>
        </template>
    </FilterContainer>
</template>

<script>
import Multiselect from '@vueform/multiselect'
import debounce from 'lodash/debounce'

export default {
    emits: ['change'],
    components: { Multiselect },

    props: {
        resourceName: { type: String, required: true },
        filterKey: { type: String, required: true },
        lens: String,
    },

    data: () => ({
        value: [],
        useAny: false,
        debouncedEmit: null,
        isLoading: false,
    }),

    created() {

        this.debouncedHandleChange = debounce(() => this.handleChange(), 500)
        this.debouncedEmit = debounce(() => this.emitChange(), 500)
        this.setCurrentFilterValue()
    },

    mounted() {
        Nova.$on('filter-reset', this.setCurrentFilterValue)
        this.$nextTick(function () {
            // Code that will run only after the
            // entire view has been rendered
            if (!this.isLoading)
                this.asyncFind('');
        })
    },

    beforeUnmount() {
        Nova.$off('filter-reset', this.setCurrentFilterValue)
    },

    watch: {
        value() {
            this.debouncedHandleChange()
        },
    },

    methods: {
        setCurrentFilterValue() {
            this.value = this.filter.currentValue || []
            var pos = this.value.indexOf('::useAny');
            if (pos != -1 || this.filter.withAnyTags) {
                this.useAny = true;
            }
        },

        handleAny(emit) {
            if (this.useAny) {
                if (this.value.indexOf("::useAny") == -1) {
                    this.value.push("::useAny")
                }
            } else {
                var ind = this.value.indexOf("::useAny")
                if (ind != -1) {
                    this.value.splice(ind, 1)
                }
            }
            if (emit) {
                this.debouncedEmit()
            }
        },

        handleChange(e) {
            if (e == undefined) {
                return
            }
            this.value = e

            this.handleAny(false)
            this.debouncedEmit()
        },

        emitChange() {
            this.$emit('change', {
                filterClass: this.filterKey,
                value: this.value,
            })
        },
        asyncFind(query) {
            this.isLoading = true
            Nova.request().post('/nova-vendor/php-nova-spatie-tags-filter/tags', { q: query, tag_type: this.filter.tag_type }).then(response => {
                this.filter.options = response.data;
                this.isLoading = false
            })
        }
    },

    computed: {
        filter() {
            return this.$store.getters[`${this.resourceName}/getFilter`](
                this.filterKey
            )
        },
        placeholder() {
            return this.filter.placeholder != undefined ? this.filter.placeholder : this.filter.name;
        }

        // value() {
        //     return this.filter ? this.filter.currentValue : null
        // },
    },
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
