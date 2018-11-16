<template right>
    <v-app id="inspire">
    <v-toolbar fixed app :clipped-left="$vuetify.breakpoint.lgAndUp" :clipped-right="$vuetify.breakpoint.lgAndUp" class="white--text">
      <v-toolbar-title class="mr-2">مدیریت</v-toolbar-title>
    </v-toolbar>
    <v-content>
      <v-container fluid fill-height>
        <v-layout justify-center align-start>
          <v-flex text-xs-center>
              <form>
                <v-text-field
                    v-model="url"
                    :error-messages="urlErrors"
                    label="آدرس وب سایت"
                    placeholder="www.asriran.com"
                    required
                    @input="$v.url.$touch()"
                    @blur="$v.url.$touch()"
                ></v-text-field>
                <v-text-field
                    v-model="tagBox"
                    :error-messages="tagBoxErrors"
                    label="فیلتر باکس"
                    placeholder="article.box2"
                    required
                    @input="$v.tagBox.$touch()"
                    @blur="$v.tagBox.$touch()"
                ></v-text-field>
                <v-text-field
                    v-model="tagTitle"
                    :error-messages="tagTitleErrors"
                    label="فیلتر عنوان"
                    placeholder="a.title3"
                    required
                    @input="$v.tagTitle.$touch()"
                    @blur="$v.tagTitle.$touch()"
                ></v-text-field>
                <v-text-field
                    v-model="tagBody"
                    :error-messages="tagBodyErrors"
                    label="فیلتر مطلب"
                    placeholder="div.lead1"
                    required
                    @input="$v.tagBody.$touch()"
                    @blur="$v.tagBody.$touch()"
                ></v-text-field>
                <v-btn @click="submit">جستجو</v-btn>
            </form>
            <v-card>
                <v-card-title>
                    نتایج
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
                </v-card-title>
                <v-data-table
                :headers="headers"
                :items="news"
                :search="search"
                >
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.title }}</td>
                    <td class="text-xs-right">{{ props.item.body }}</td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
                </v-data-table>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
    <v-footer fixed app inset>
      <v-flex
        py-2
        text-xs-center
        white--text
        xs12
      >
        {{ new Date().getFullYear() }}&copy; — <strong>MZ</strong>
      </v-flex>
      <v-flex>
      </v-flex>
    </v-footer>
  </v-app>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, maxLength, email } from 'vuelidate/lib/validators'
    import axios from 'axios'

    export default {
        mixins: [validationMixin],

        validations: {
            url: { required },
            tagTitle: { required },
            tagBox: { required },
            tagBody: { required },
        },

        data: () => ({
            tagBox: '',
            tagTitle: '',
            tagBody: '',
            url: '',
            news: [],
            search: '',
            headers: [
                {
                    text: 'عنوان',
                    align: 'center',
                    value: 'title'
                },
                {
                    text: 'متن',
                    align: 'center',
                    value: 'body'
                },
            ],
        }),

        computed: {
            urlErrors () {
                const errors = []
                if (!this.$v.url.$dirty) return errors
                !this.$v.url.required && errors.push('آدرس را وارد نمایید.')
                return errors
            },
            tagBoxErrors () {
                const errors = []
                if (!this.$v.tagBox.$dirty) return errors
                !this.$v.tagBox.required && errors.push('تگ ها را وارد نمایید.')
                return errors
            },
            tagTitleErrors () {
                const errors = []
                if (!this.$v.tagTitle.$dirty) return errors
                !this.$v.tagTitle.required && errors.push('تگ ها را وارد نمایید.')
                return errors
            },
            tagBodyErrors () {
                const errors = []
                if (!this.$v.tagBody.$dirty) return errors
                !this.$v.tagBody.required && errors.push('تگ ها را وارد نمایید.')
                return errors
            }
        },

        methods: {
            submit () {
                if(!this.$v.$touch()) {
                    axios.post('/crawl', {
                        url: this.url,
                        tagBox: this.tagBox,
                        tagTitle: this.tagTitle,
                        tagBody: this.tagBody
                    }).then(response => {
                        this.news = response.data.content
                        this.url = '',
                        this.tagBox = '',
                        this.tagTitle = '',
                        this.tagBody = ''
                    }).catch(function(error) {
                        this.url = '',
                        this.tagBox = '',
                        this.tagTitle = '',
                        this.tagBody = ''
                    });
                }

            },
        }
    }
</script>
