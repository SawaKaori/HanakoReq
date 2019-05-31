<!--
/**
 * RequestList.vue
 * 依頼の一覧表示
 * Copyright (C) 2019 J.Kawahara 新規作成
 * 2019.3.18 J.Kawahara 新規作成
 */
-->
<template>
    <div>
         <el-table
            v-loading="loading"
            :default-sort="{prop: 'id', order: 'descending'}"
            :cell-class-name="cellClassName"
            :data="records"> <!-- ←」これだろ！　-->
            <el-table-column
                sortable
                :sort-method="sortMethodRegisted"
                label="登録日"
                width="100px">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        @click="editRequest(scope.row.id)"
                        :type="buttonType">{{ dateStr(scope.row.registed_dt) }}</el-button>
                </template>
            </el-table-column>
            <el-table-column
                sortable
                prop="sender"
                label="依頼者"
                width="80px"></el-table-column>
            <el-table-column
                sortable
                :sort-method="sortMethodLimit"
                prop="limit_dt"
                label="期限"
                :formatter="formatterDate"
                width="100px"></el-table-column>
            <el-table-column
                sortable
                prop="senders_comment"
                label="内容">
                <template slot-scope="scope">
                    <span v-html="commentToHtml(scope.row.senders_comment)"></span>

                    <ul v-if="scope.row.uploaded_files.length > 0"
                        style="padding-left: 0;">
                        <li v-for="file in scope.row.uploaded_files" :key="file.id">
                            <a :href="downloadUrl(file.s3_key)">
                                {{ file.original_name }}
                            </a>
                        </li>
                    </ul>

                    <div style="text-align: right;">
                        <el-button
                            type="primary"
                            icon="el-icon-upload"
                            @click="uploadFile(scope.row.id)"
                            size="mini" circle></el-button>
                    </div>
                </template>
                </el-table-column>
            <el-table-column
                sortable
                prop="request_type"
                :formatter="formatterRequestType"
                label="依頼区分"
                width="120px"></el-table-column>
            <el-table-column
                sortable
                prop="worker"
                label="担当者"
                width="100px">
                <template slot-scope="scope">
                    <ul>
                        <li v-if="scope.row.worker !== null"
                            >{{ scope.row.worker }}</li>
                        <li v-if="scope.row.worker_sub !== null"
                            >{{ scope.row.worker_sub }}</li>

                    </ul>
                </template>
                </el-table-column>
            <el-table-column
                sortable
                :sort-method="sortMethodStatus"
                label="状態"
                align="center"
                width="90px">
                <template slot-scope="scope">
                    <el-button
                        :type="statusType(scope.row.status)"
                        size="mini"
                        @click="editStatus(scope.row.id)">
                        {{ strStatus(scope.row.status )}}
                    </el-button>
                </template>
            </el-table-column>
            <el-table-column
                sortable
                :sort-method="sortMethodCompleted"
                prop="complete_dt"
                :formatter="formatterDate"
                label="完了日"
                width="100px"></el-table-column>
            <el-table-column
                sortable
                prop="workers_comment"
                label="コメント">
                <template slot-scope="scope">
                     <ul>
                         <li v-for="cmt in scope.row.comments" :key="cmt.id">
                             <p v-if="cmt.comment_dt">{{cmt.comment_dt}} : {{cmt.sender}}</p>
                            {{ commentToHtml(cmt.comment) }}
                    <el-button type="primary"
                        size="mini"
                        @click="delComment(cmt)">
                        削除
                    </el-button>                        </li>
                    </ul>
            

                    <el-button type="primary"
                        size="mini"
                        @click="editComment(scope.row.id)">
                        コメント登録
                    </el-button>
                </template>
                </el-table-column>
            <el-table-column
                sortable
                :sort-method="sortMethodConfirmStatus"
                label="依頼者確認"
                align="center"
                width="140px">
                <template slot-scope="scope">
                    <el-button
                        :type="confirmType(scope.row.confirm_status)"
                        size="mini"
                        @click="confirm(scope.row.id)">
                        {{ strConfirm(scope.row.confirm_status) }}
                    </el-button>
                </template>
            </el-table-column>
 
            <el-table-column
                sortable
                :sort-method="sortMethodConfirmed"
                prop="confirm_dt"
                :formatter="formatterDate"
                label="確認完了日"
                width="100px"></el-table-column>
        </el-table>

    </div>
    
</template>
    
<script>
import Defines from '~/components/Defines.vue';
import CommentList from '~/components/CommentList.vue';

export default {
    props: [
        'loading',
        'buttonType',
        'records'
    ],
    data() {
        return {
            nowDt: new Date(),
        }
    },
    mixins: [
        Defines
    ],
    components: {
        'comment-list': CommentList,
    },
    methods: {
        commentToHtml(comment) {
            // const html = comment.replace(/\r?\n/g, '<br>');
            if (!comment) {
                return '';
            }
            const lines = String(comment).split('\n');
            const regex = new RegExp('^http.*$');
            let html = '';
            for (let i in lines) {
                const link = (lines[i].trim().match(regex));
                if (link) {
                    html +=  ('<a target="_blank" href="' + link + '">' + link + '</a>');
                } else {
                    html += lines[i];
                }
                if (i < lines.length) {
                    html += '<br>';
                }
            }
            return html;
        },
        commentList(id){
                this.$axios.$get('/comment/list',{
                    params: {

                    }
                })
                
                .then((response) => {
                    this.recordList = records;
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                });
        },
        downloadUrl(s3_key) {
            return  process.env.API_URL + '/download/' + s3_key;
        },
        editRequest(id) {
            this.$emit('update', 'editRequest', id);
        },
        editStatus(id) {
            this.$emit('update', 'editStatus', id);
        },
        editComment(id) {
            this.$emit('update', 'editComment', id);
        },
        uploadFile(id) {
            this.$emit('update', 'uploadFile', id);
        },
        confirm(id) {
            this.$emit('update', 'confirm', id);
        },
        delComment(cmt){
             this.$confirm('この依頼を削除します。よろしいですか？', '削除の確認', {
                confirmButtonText: '削除します',
                cancelButtonText: 'キャンセル',
                type: 'warning'
            })
            .then(() => {
                this.$emit('remove', cmt);
            });
        },
        cellClassName({row, column, rowIndex, columnIndex}) {
            if (columnIndex == 2) {
                if (row.limit_dt != '') {
                    const limitDt = new Date(row.limit_dt);
                    if (limitDt < this.nowDt) {
                        return 'expired';
                    }
                }
            }
            return '';
        },
        dateStr(strDt) {
            if (!strDt) {
                return '';
            }
            const dt = new Date(strDt);
            return ((dt.getMonth() + 1) + '月' + dt.getDate() + '日');
        },
        formatterDate(row, column, cellValue, index) {
            return this.dateStr(cellValue);
        },
        formatterRequestType(row, column, cellValue, index) {
            if (cellValue == -1) {
                return '';
            }
            const rec = this.RequestTypeList.find((rec) => {
                return (rec.id == cellValue);
            });
            if (rec === undefined) {
                return '???';
            }
            return rec.name;
        },
        statusType(status) {
            if (status == 1) {
                return 'info';
            } else if (status == 2) {
                return 'warning';
            } else if (status == 3) {
                return 'success';
            }
            return '';
        },
        strStatus(status) {
            const rec = this.StatusList.find((rec) => {
                return (rec.id == status);
            });
            if (rec === undefined) {
                return '???';
            }
            return rec.name;
        },
        confirmType(confirm_status) {
            if (confirm_status == 1) {
                return 'primary';
            }
            return '';
        },
        strConfirm(confirm_status) {
            const rec = this.ConfirmStatusList.find((rec) => {
                return (rec.id == confirm_status);
            });
            if (rec === undefined) {
                return '???';
            }
            return rec.name;
        },

        /** ソート関連の関数 */
        sortMethodId(a, b) {
            return (a.id - b.id);
        },
        dateDiff(a, b) {
            const at = a ? new Date(a).getTime() : 0;
            const bt = b ? new Date(b).getTime() : 0;
            return at - bt;
        },
        sortMethodRegisted(a, b) {
            return (this.dateDiff(a.registed_dt, b.registed_dt));
        },
        sortMethodLimit(a, b) {
            reutrn (this.dateDiff(a.limit_dt, b.limit_dt));
        },
        sortMethodCompleted(a, b) {
            return (this.dateDiff(a.complete_dt, b.complete_dt));
        },
        sortMethodConfirmed(a, b) {
            return (this.dateDiff(a.confirm_dt, b.confirm_dt));
        },
        sortMethodStatus(a, b) {
            return (a.status - b.status);
        },
        sortMethodConfirmStatus(a, b) {
            return (a.confirm_status - b.confirm_status);
        },
        spanLogicMethod({ row, column, rowIndex, columnIndex }){
            if (columnIndex === 0) { 
                if (rowIndex % 2 === 0) { 
                     return { 
                        rowspan: 0, 
                        colspan: 0 
                    }; 
                } else { 
                  return { 
                     rowspan: 0, 
                     colspan: 10 
                  }; 
                } 
            } 
        }
    }
}
</script>

<style>
.expired {
    background-color: rgb(255, 255, 180);
}
</style>
