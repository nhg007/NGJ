<template>
    <div class="takeout-container">
        <div class="app-main-container">
            <el-tabs v-model="editableTabsValue" type="card" editable @edit="handleTabsEdit">
                <el-tab-pane
                    :key="item.name"
                    v-for="(item, index) in editableTabs"
                    :label="item.title"
                    :name="item.name"
                >
                    <component :is="item.content" :date="item.name"></component>
                </el-tab-pane>
            </el-tabs>

        </div>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                editableTabsValue: '2',
                editableTabs: [],
                tabIndex: 0,
                today:new Date(),
                form:{
                    takeDate:''
                }
            }
        },
        created(){
            this.initTabs()
        },
        methods: {
            initTabs(){
                //正常来说，获取当天以后的数据
                let today = moment(this.today).format('YYYY-MM-DD')
                let _this = this;
                this.form.takeDate = today
                axios.post('/restrant/takeout/getTakeDates', this.form).then(function (response) {
                    if (response.status === 200) {
                        const {data} = response.data
                        if(data.length > 0){
                            data.forEach(p=>{
                                _this.editableTabs.push({
                                    title: p,
                                    name: p,
                                    content: TakeoutItemComponent
                                });
                            })
                        }else{
                            _this.editableTabs.push({
                                title:today ,
                                name: today,
                                content: TakeoutItemComponent
                            });
                        }
                        _this.editableTabsValue = today;
                    }
                }).catch(function (error) {
                    _this.$baseMessage('データの取得を失敗しました', 'error')
                });
            },
            handleTabsEdit(targetName, action) {
                if (action === 'add') {
                    this.tabIndex ++
                    let newTabName = new moment(this.today).add(this.tabIndex,'days').format('YYYY-MM-DD')
                    this.editableTabs.push({
                        title: newTabName,
                        name: newTabName,
                        content: TakeoutItemComponent
                    });
                    this.editableTabsValue = newTabName;
                }
                if (action === 'remove') {
                    this.tabIndex --
                    let tabs = this.editableTabs;
                    let activeName = this.editableTabsValue;
                    if (activeName === targetName) {
                        tabs.forEach((tab, index) => {
                            if (tab.name === targetName) {
                                let nextTab = tabs[index + 1] || tabs[index - 1];
                                if (nextTab) {
                                    activeName = nextTab.name;
                                }
                            }
                        });
                    }

                    this.editableTabsValue = activeName;
                    this.editableTabs = tabs.filter(tab => tab.name !== targetName);
                }
            }
        }
    }
</script>

<style scoped>
    .el-tabs {
        padding: 20px;
    }
.el-tabs__content {
    padding: 20px;
}

.el-tabs__nav .el-tabs__item:nth-child(1) span{
   display: none;
}
</style>
