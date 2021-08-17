<template>
    <el-form ref="form" :model="form" :rules="rules">
        <el-form-item class="time_line_container">
            <el-row>
                <el-col>時間帯設定：</el-col>
            </el-row>
            <el-row v-for="(type, index) in form.timelines" :key="index">
                <el-col :xs="24" :xl="8">
                    <div class="grid-content bg-purple">
                        <el-form-item>
                            <el-col :xs="6" :xl="8" :lg="8">
                                <el-form-item
                                    :prop="'timelines.' + index + '.start_time'"
                                    :rules="[
                                              {
                                                required: true,
                                                message: '開始時間を入力してください',
                                                trigger: 'blur',
                                              },
                                            ]"
                                >
                                    <el-time-select
                                        placeholder="開始時間"
                                        v-model="type.start_time"
                                        @change="onStartTimeChange($event,index)"
                                        :disabled="isDisabled(index)"
                                        :picker-options="{
                                          start: start,
                                          step: '00:60',
                                          end: end,
                                          minTime: type.preEndTime
                                        }">
                                    </el-time-select>
                                </el-form-item>
                            </el-col>
                            <el-col class="line" :span="4">-</el-col>
                            <el-col :xs="6" :xl="8" :lg="8">
                                <el-form-item
                                    :prop="'timelines.' + index + '.end_time'"
                                    :rules="[
                                          {
                                            required: true,
                                            message: '終了時間を入力してください',
                                            trigger: 'blur',
                                          }
                                        ]"
                                >
                                    <el-time-select
                                        placeholder="終了時間"
                                        v-model="type.end_time"
                                        @change="onEndTimeChange($event,index)"
                                        :disabled="isDisabled(index)"
                                        :picker-options="{
                                          start: start,
                                          step: '00:60',
                                          end: end,
                                          minTime: type.start_time
                                        }">
                                    </el-time-select>
                                </el-form-item>
                            </el-col>
                        </el-form-item>
                    </div>
                </el-col>
                <el-col :xs="24" :xl="4">
                    <el-input-number v-model.number="type.number" :min="0" :max="999" :disabled="isDisabled(index)"
                                     autocomplete="off"></el-input-number>
                    個
                </el-col>
                <el-col :xs="24" :xl="4">
                    <el-button
                        v-if="index == 0"
                        type="primary"
                        class="button-new-tag"
                        size="small"
                        icon="el-icon-plus"
                        @click="addTimeline(index)"
                    >
                        添加
                    </el-button>
                    <el-button
                        v-else
                        type="danger"
                        class="button-new-tag"
                        size="small"
                        icon="el-icon-delete"
                        :disabled="isDisabled(index)"
                        @click="decreaseTimeline(index)"
                    >
                        删除
                    </el-button>
                </el-col>
            </el-row>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="onSubmit">提交</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
module.exports = {
    props: {
        date: {
            type: String,
            default: ''
        },
        list: {
            type: Object,
            default: () => {
                return {}
            },
        }
    },
    data() {
        return {
            start: '10:00',
            end: '22:00',
            form: {
                takeDate: '',
                timelines: [
                    {
                        start_time: '',
                        end_time: '',
                        preEndTime: '',
                        number: 0,
                    },
                ],
            },
            rules: {},
        }
    },
    created() {
        this.fetchData()
    },
    mounted() {
    },
    methods: {
        addTimeline() {
            //检查timeline中是否有空数据，如果有空的情况下，是不允许再追加空行
            if (_.some(this.form.timelines, {'start_time': ''})) {
                this.$baseMessage('テイクアウト来店時間帯を設定してください', 'error')
                return;
            }
            let index = this.form.timelines.length
            let lastEndTime =  this.form.timelines[index - 1].end_time;
            this.form.timelines.push({
                start_time: '',
                end_time: '',
                preEndTime: moment(this.date + ' ' + lastEndTime).subtract(1,'hours').format('HH:mm'),
                number: 0,
            })
        },
        decreaseTimeline(index) {
            this.form.timelines.splice(index, 1)
        },
        fetchData() {
            //获取指定日期的数据
            // console.info(this.date )
            let _this = this;
            this.form.takeDate = this.date;
            //根据take获取数据
            axios.post('/restrant/takeOut/getLists', this.form).then(function (response) {
                console.info(response)
                if (response.status === 200) {
                    if(response.data.data.length > 0){
                        let res = response.data.data;
                        let list = [];
                        //format 数据
                        for(let i = 0;i<res.length;i++){
                            if(i > 0){
                                res[i].preEndTime =  moment(_this.date + ' ' + res[i - 1].end_time).subtract(1,'hours').format('HH:mm')
                            }
                            list.push(res[i]);
                        }
                        _this.form.timelines = list;
                    }
                }
            }).catch(function (error) {
                _this.$baseMessage('データの取得を失敗しました', 'error')
            });

        },
        onSubmit() {
            this.$refs['form'].validate(async (valid) => {
                if (valid) {
                    let _this = this
                    axios.post('/restrant/takeOut/update', this.form).then(function (response) {
                        if (response.status == 200) {
                            _this.$baseMessage('保存完了しました', 'success')
                        }
                    }).catch(function (error) {
                        _this.$baseMessage('保存失敗しました', 'error')
                    });
                }
            })
        },
        onStartTimeChange(v, i) {

        },
        onEndTimeChange(v, i) {
            //更新下一条数据的preEndTime
            if (i + 1 < this.form.timelines.length) {
                this.form.timelines[i + 1].preEndTime = v
                this.form.timelines[i + 1].start_time = ''
                this.form.timelines[i + 1].end_time = ''
            }

            let start = i + 2;
            while (start < this.form.timelines.length) {
                this.form.timelines[start].start_time = ''
                this.form.timelines[start].end_time = ''
                start++
            }
        },
        isDisabled(index){

            let now = new Date();
            let startTime = this.form.timelines[index].start_time;
            let endTime = this.form.timelines[index].end_time;
           if(!startTime){
               return  false
           }

           if(!endTime){
               return  false
           }

           if(moment(now) - moment(this.date + ' ' + endTime) > 0){
               return true
           }
            return false
        }
    }
}
</script>

<style scoped>
.time_line_container .el-row {
    margin-bottom: 20px;
}
</style>
