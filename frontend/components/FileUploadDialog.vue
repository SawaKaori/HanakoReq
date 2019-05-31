<!--
/**
 * FileUploadDialog.vue
 * ファイルアップロードダイアログ
 * Copyright (C) 2019 J.Kawahara 新規作成
 * 2019.3.31 J.Kawahara 新規作成
 */
-->
<template>
    <el-dialog
        title="ファイルアップロード"
        :visible.sync="dialogVisible">
        <div style="text-align: center;">
            <el-upload
                class="upload-file"
                action=""
                drag
                multiple
                :limit="5"
                :on-change="handleAdd"
                :on-remove="handleRemove"
                :auto-upload="false"
                :file-list="fileList">
                <i class="el-icon-upload"></i>
                <div class="el-upload__text">
                    ここにファイルをドラッグ＆ドロップ
                </div>
            </el-upload>
            <div style="margin-top: 12px">
                <el-button type="primary"
                    size="mini"
                    @click="upload"
                    :disabled="fileList.length <= 0">アップロード</el-button>
            </div>
            <div v-if="uploadedList.length > 0">
                <el-table
                    :data="uploadedList"
                    style="width: 100%;">
                    <el-table-column
                        prop="original_name"
                        label="ファイル名"></el-table-column>
                    <el-table-column
                        width="80px"
                        align="center"
                        label="削除">
                        <template slot-scope="scope">
                            <el-button type="info" icon="el-icon-delete"
                                @click="remove(scope.row.id)"
                                size="mini"></el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button type="info"
                @click="close()">閉じる</el-button>
        </span>
    </el-dialog>
</template>
<script>
export default {
    data() {
        return {
            dialogVisible: false,
            record: {},
            fileList: [],
            uploadedList: [],
            erased: false,
        }
    },
    methods: {
        open(record) {
            this.record = record;
            this.uploadedList = record.uploaded_files;

            this.dialogVisible = true;
        },
        close() {
            if (this.erased) {
                this.$emit('refresh');
            }
            this.dialogVisible = false;
        },
        handleRemove(file, fileList) {
            this.fileList = fileList
        },
        handleAdd(file, fileList) {
            this.fileList = fileList
        },
        upload() {
            if (this.fileList.length <= 0) {
                return;
            }
            const formData = new FormData();
            formData.append('request_id', this.record.id);
            for (let i = 0; i < this.fileList.length; ++i) {
                formData.append('files[' + i + ']', this.fileList[i].raw);
            }
            this.$axios.post('/upload', formData)
                .then((response) => {
                    if (!response.data.result) {
                        alert('ファイルのアップロードに失敗しました。');
                        return;
                    }
                    this.$emit('refresh');
                    this.close();
                })
                .catch((error) => {
                    alert('ファイルのアップロードに失敗しました');
                });
        },
        remove(id) {
            const rec = this.uploadedList.find((rec) => {
                return (rec.id == id);
            });
            if (rec == undefined) {
                return;
            }
            const message = rec.original_name + 'を削除します。よろしいですか？';
            this.$confirm(message, 'ファイルの削除', {
                confirmButtonText: 'はい',
                cancelButtonText: 'いいえ',
                type: 'warning'
            })
            .then(() => {
                this.$axios.delete(`/remove/${rec.id}`)
                    .then((response) => {
                        if (response.data.result) {
                            this.erased = true;
                            this.uploadedList = this.uploadedList.filter(rec => rec.id != id);
                        } else {
                            alert('ファイルの削除に失敗しました。');
                        }
                    })
                    .catch((error) => {
                        alert('ファイルの削除に失敗しました');
                    });
            });
        }
    }
}
</script>
