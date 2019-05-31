<!--
/**
 * index.vue
 * SiteReq WEB制作依頼システム トップページ
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.16 J.Kawahara 新規作成
 */
-->
<template>
    <section class="section">
        <div class="top-area">
            <div class="criteria-header">
                絞り込み条件
            </div>
            <div class="criteria-panel">
                <el-form :inline="true" size="mini">

                    <el-form-item label="依頼者">
                        <el-input v-model="searchSender" placeholder="依頼者"></el-input>
                    </el-form-item>

                    <el-form-item label="内容">
                        <el-input v-model="searchSendersComment"
                            placeholder="内容">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="依頼区分">
                        <el-select v-model="searchRequestType"
                            placeholder="依頼区分">
                            <el-option v-for="reqType in s_requestTypeList"
                                :key="reqType.id"
                                :label="reqType.name"
                                :value="reqType.id"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="担当者">
                        <el-select v-model="searchWorker"
                            placeholder="担当者">
                            <el-option v-for="worker in s_workerList"
                                :key="worker"
                                :label="worker"
                                :value="worker"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="状態">
                        <el-select v-model="searchStatus"
                            placeholder="状態">
                            <el-option v-for="item in s_statusList"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="コメント">
                        <el-input v-model="searchWorkersComment"
                            placeholder="コメント">
                        </el-input>
                        
                    </el-form-item>

                    <el-form-item label="確認欄">
                        <el-select v-model="searchConfirmStatus"
                            placeholder="確認欄">
                            <el-option v-for="item in s_confirmStatusList"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </div>
        </div>
        <div>
            <el-row>
                <el-col :span="12">
                    <el-button type="primary"
                        size="mini"
                        @click="openEditRequestDialog(0)">
                        新規登録
                    </el-button>
                </el-col>
            </el-row>
        </div>

        <!-- 急ぎのリスト -->
        <div v-if="showHurryList">
            <h2>急ぎの案件一覧</h2>
            <request-list
                buttonType="danger"
                :records="hurryRecords"
                :loading="loading"
                @update="update"
                @remove="removeRequest"
                id="hullyList"></request-list>
        </div>

        <!-- リスト -->
        <div>
            <h2>依頼案件の一覧</h2>
            <request-list
                buttonType="primary"
                :records="records"
                :loading="loading"
                @update="update"
                @remove="removeComment"
                id="requestList"></request-list>
        </div>

        <!-- 編集ダイアログ -->
        <edit-request-dialog
            @submit="storeRequest"
            @remove="removeRequest"
            :adminMode="adminMode"
            ref="EditRequestDialog"></edit-request-dialog>
                
        <!-- 状態編集ダイアログ -->
        <edit-status-dialog
            @submit="storeRequest"
            ref="EditStatusDialog"></edit-status-dialog>
        
        <!-- ファイルアップロードダイアログ -->
        <file-upload-dialog
            @refresh="loadList"
            ref="FileUploadDialog"></file-upload-dialog>
        
        <!-- コメント編集ダイアログ -->
        <edit-comment-dialog
            @submit="storeComment"
             ref="EditCommentDialog"></edit-comment-dialog>
   </section>
</template>

<script>
import Defines from '~/components/Defines.vue';
import EditRequestDialog from '~/components/EditRequestDialog.vue';
import EditStatusDialog from '~/components/EditStatusDialog.vue';
import FileUploadDialog from '~/components/FileUploadDialog.vue';
import EditCommentDialog from '~/components/EditCommentDialog.vue';
import RequestList from '~/components/RequestList.vue';

export default {
    head() {
        return {
            title: 'WEB制作依頼'
        }
    },
    mixins: [
        Defines
    ],
    components: {
        'request-list': RequestList,
        'edit-request-dialog': EditRequestDialog,
        'edit-status-dialog': EditStatusDialog,
        'file-upload-dialog': FileUploadDialog,
        'edit-comment-dialog': EditCommentDialog,
    },
    data() {
        return {
            adminMode: false,
            recordList: [],
            loading: true,
            searchSender: '',
            searchWorker: '',
            searchRequestType: -1,
            searchSendersComment: '',
            searchWorkersComment: '',
            searchStatus: -1,
            searchConfirmStatus: 0,
        };
    },
    computed: {
        s_senderList() {
            return [''].concat(this.SenderList);
        },
        s_workerList() {
            return [''].concat(this.WorkerList);
        },
        s_statusList() {
            return [{ id: -1, name: ' ' }].concat(this.StatusList);
        },
        s_confirmStatusList() {
            return [{ id: -1, name: ' ' }].concat(this.ConfirmStatusList);
        },
        s_requestTypeList() {
            return [{ id: -1, name: ' ' }].concat(this.RequestTypeList);
        },
        showHurryList() {
            return (this.hurryRecords.length > 0);
        },
        records() {
            return this.recordList.filter(
                data => {
                    if ((data.hurry_flag == 1)
                     || (this.searchSender
                            && (data.sender === null || !data.sender.includes(this.searchSender)))
                     || (this.searchWorker
                            && (data.worker === null || !data.worker.includes(this.searchWorker)))
                     || (this.searchSendersComment
                            && (data.senders_comment == null || !data.senders_comment.includes(this.searchSendersComment)))
                     || (this.searchWorkersComment
                            && (data.workers_comment == null || !data.workers_comment.includes(this.searchWorkersComment)))
                     || (this.searchRequestType >= 0 && data.request_type != this.searchRequestType)
                     || (this.searchStatus >= 0 && data.status != this.searchStatus)
                     || (this.searchConfirmStatus >= 0 && data.confirm_status != this.searchConfirmStatus)
                    ) {
                        return false;
                    }
                    return true;
                }
            );
        },
        hurryRecords() {
            return this.recordList.filter(
                data => {
                    if ((data.hurry_flag != 1)
                     || (this.searchConfirmStatus >= 0 && data.confirm_status != this.searchConfirmStatus)
                    ) {
                         return false;
                    }
                    return true;
                }
            );
        }
    },
    methods: {
        dateToStr(dt) {
            const y = dt.getFullYear();
            const m = dt.getMonth() + 1;
            const d = dt.getDate();
            return y + '/' + m + '/' + d;
        },
        loadList() {
            this.$axios.$get('/requestItem/list')
                .then((response) => {
                    this.recordList = response.records;
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                });
        },
        update(task, id) {
            if (task == 'editRequest') {
                this.openEditRequestDialog(id);
            } else if (task == 'editStatus') {
                this.openStatusDialog(id);
            } else if (task == 'uploadFile') {
                this.openFileUploadDialog(id);
            } else if (task == 'editComment') {
                this.openEditCommentDialog(id);                
            } else if (task == 'confirm') {
                this.switchConfirm(id);
            }
        },
        openEditRequestDialog(id) {
            let record = this.recordList.find((rec) => {
                return (rec.id == id);
            });
            if (record === undefined) {
                record = {
                    request_type: -1,
                    status: 0,
                    confirm_status: 0,
                    hurry_flag: 0,
                    sender: '',
                    worker: '',
                    product_code: '',
                    product_name: '',
                    senders_comment: '',
                    workers_comment: '',
                    registed_dt: this.dateToStr(new Date()),
                    limit_dt: '',
                    complete_dt: '',
                    confirm_dt:''
                }
            }
            this.$nextTick(() => {
                this.$refs.EditRequestDialog.open(record);
            });
        },
        openStatusDialog(id) {
            const record = this.recordList.find((rec) => {
                return (rec.id == id);
            });
            this.$nextTick(() => {
                this.$refs.EditStatusDialog.open(record);
            });
        },
        openFileUploadDialog(id) {
            const record = this.recordList.find((rec) => {
                return (rec.id == id);
            });
            this.$nextTick(() => {
                this.$refs.FileUploadDialog.open(record);
            });
        },
        openEditCommentDialog(id) {
            const record = this.recordList.find((rec) => {
                return (rec.id == id);
            });
            this.$nextTick(() => {
                this.$refs.EditCommentDialog.open(record);
            });
        }, 
        switchConfirm(id) {
            const record = this.recordList.find((rec) => {
                return (rec.id == id);
            });
            if (record === undefined) {
                return;
            }
            if (record.confirm_status == 0) {
                record.confirm_status = 1;
                record.confirm_dt = this.dateToStr(new Date());
            } else {
                record.confirm_status = 0;
                record.confirm_dt = '';
            }
            this.storeRequest(record);
        },
        storeRequest(record) {
            this.$axios.$post('/requestItem/store', record)
                .then((response) => {
                    this.$refs.EditRequestDialog.close();
                    this.$refs.EditStatusDialog.close();
                    this.loadList();
                })
                .catch((error) => {
                    alert('登録に失敗しました。');
                });
        },
        removeRequest(record) {
            const param = { id: record.id };
            this.$axios.$delete('/requestItem/remove', {data: param})
                .then((response) => {
                    this.$refs.EditRequestDialog.close();
                    this.loadList();
                })
                .catch((error) => {
                    alert('削除に失敗しました。');
                });
        },
        storeComment(record) {
            this.$axios.$post('/comment/store', record)
                .then((response) => {
                    this.$refs.EditCommentDialog.close();
                    this.loadList();
                })
                .catch((error) => {
                    alert('登録に失敗しました。');
                });
        },
        removeComment(record) {
            const param = { id: record.id };
            this.$axios.$delete('/comment/remove', {data: param})
                .then((response) => {
                    this.$refs.EditRequestDialog.close();
                    this.loadList();
                })
                .catch((error) => {
                    alert('削除に失敗しました。');
                });
        },
    },
    mounted: function() {
        this.loadList();
        this.adminMode = ('hc' in this.$nuxt.$route.query);
    }
}
</script>
