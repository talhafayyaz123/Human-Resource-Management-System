<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="flex flex-wrap">
            <div class="lg:w-8/12 p-2 w-full">
                <div class="card w-full bg-white rounded-md shadow p-3">
                    <div class="flex justify-between py-2">
                        <div class="flex">
                            <p
                                class="mr-5"
                                style="text-align: center; color: gray"
                            >
                                Ticket{{ ticket.ticketNumber }}
                            </p>
                            <h1 style="color: #2957a4; font-weight: bold">
                                {{ ticket.title }}
                            </h1>
                        </div>
                        <p style="text-align: center; color: gray">
                            {{ $t("Contact") }} {{ $t("Type") }}:
                            {{ $dashedToRegularFormat(ticket?.contactType) }}
                        </p>
                    </div>
                    <div class="relative mb-5">
                        <div class="form-group">
                            <QuillTextEditor
                                v-if="shouldShow && !toggleEditModal"
                                class="w-full"
                                :content="ticketForm.text"
                                :content-type="'html'"
                                :required="true"
                                :error="errors.text"
                                @delta="ticketForm.text = $event"
                            />
                        </div>
                        <div class="custom-dropdone mt-3">
                            <div
                                class="dropzone-area"
                                v-if="!isReadonly"
                                @drop="dropHandler"
                                @dragover="dragOverHandler"
                            >
                                <div class="icon">
                                    <CustomIcon name="uploadIcon" />
                                </div>
                                <div class="text">
                                    <h3>Select a file or drag and drop here</h3>
                                </div>
                                <input
                                    id="hidden-input"
                                    type="file"
                                    ref="file"
                                    @change="addFiles"
                                    name="files[]"
                                    multiple
                                    class="hidden"
                                />
                                <button
                                    @click="$refs.file.click()"
                                    id="button"
                                    type="button"
                                    class="secondary-btn gap-2"
                                >
                                    <CustomIcon name="uploadsmalIcon" />
                                    {{ $t("Upload a file") }}
                                </button>
                            </div>
                            <div class="mt-3">
                                <h3 v-if="!isReadonly" class="card-title">
                                    {{ $t("To Upload") }}
                                </h3>
                            </div>
                            <div class="dropzone-list">
                                <ul
                                    class="grid items-center grid-cols-2 gap-6 mt-3"
                                >
                                    <li v-if="commentFiles.length === 0">
                                        <h3>{{ $t("No Files Selected") }}</h3>
                                    </li>
                                    <li
                                        v-else
                                        v-for="(file, index) in commentFiles"
                                        :key="index"
                                    >
                                        <div class="dropzone-box">
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <div class="icon">
                                                    <CustomIcon
                                                        name="uploaddefaultIcon"
                                                    />
                                                </div>
                                                <div>
                                                    <h3>{{ file.name }}</h3>
                                                    <p
                                                        v-if="file.size"
                                                        class=""
                                                    >
                                                        {{
                                                            file.size > 1024
                                                                ? file.size >
                                                                  1048576
                                                                    ? Math.round(
                                                                          file.size /
                                                                              1048576
                                                                      ) + "mb"
                                                                    : Math.round(
                                                                          file.size /
                                                                              1024
                                                                      ) + "kb"
                                                                : file.size +
                                                                  "b"
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex pb-2">
                                                <img
                                                    v-if="
                                                        showPreview &&
                                                        !!file?.base64
                                                    "
                                                    :src="
                                                        'data:image\/png;base64,' +
                                                        file?.base64
                                                    "
                                                    style="
                                                        width: 55px !important;
                                                        height: 40px !important;
                                                        margin-right: 10px;
                                                    "
                                                />

                                                <button
                                                    v-if="!isReadonly"
                                                    @click="removeFile(index)"
                                                    class="delete"
                                                    type="button"
                                                >
                                                    <CustomIcon
                                                        name="DeleteIcon"
                                                    />
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-5">
                            <div class="grid items-center grid-cols-3 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            v-model="ticketForm.visibility"
                                            :key="ticketForm.visibility"
                                            :required="true"
                                            :label="$t('Visibility')"
                                            :error="errors?.visibility ?? ''"
                                        >
                                            <option value="internal">
                                                {{ $t("Internal Only") }}
                                            </option>
                                            <option value="public">
                                                {{ $t("Public") }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <number-input
                                            :roundNearQuarter="true"
                                            :forceLeftBound="true"
                                            :showPrefix="false"
                                            :canBeNull="true"
                                            v-model="ticketForm.time"
                                            :required="true"
                                            :label="$t('Accounted Time')"
                                            :error="errors.time ?? ''"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            @open="getUsersListing"
                                            v-model="informedUsers"
                                            :options="users"
                                            :text-label="$t('Users To Inform')"
                                            :multiple="true"
                                            :key="informedUsers"
                                            :error="errors.informedUsers"
                                            label="first_name"
                                            trackBy="id"
                                            moduleName="auth"
                                            :query="{ type: null }"
                                            :search-param-name="'search_string'"
                                        >
                                            <template #beforeList>
                                                <div
                                                    class="grid p-2 gap-2"
                                                    style="
                                                        grid-template-columns: 50% 50%;
                                                    "
                                                >
                                                    <p class="font-bold">
                                                        {{ $t("First Name") }}
                                                    </p>
                                                    <p class="font-bold">
                                                        {{ $t("Last Name") }}
                                                    </p>
                                                </div>
                                                <hr />
                                            </template>
                                            <template #singleLabel="{ props }">
                                                <p>
                                                    {{
                                                        props.option.first_name
                                                    }}&nbsp;{{
                                                        props.option.last_name
                                                    }}
                                                </p>
                                            </template>
                                            <template #option="{ props }">
                                                <div
                                                    class="grid"
                                                    style="
                                                        grid-template-columns: 50% 50%;
                                                    "
                                                >
                                                    <p
                                                        class="overflow-text-users-table"
                                                    >
                                                        {{
                                                            props.option
                                                                .first_name
                                                        }}
                                                    </p>
                                                    <p
                                                        class="overflow-text-users-table"
                                                    >
                                                        {{
                                                            props.option
                                                                .last_name
                                                        }}
                                                    </p>
                                                </div>
                                            </template>
                                        </MultiSelectInput>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <loading-button
                                    :disabled="ticket.isArchived"
                                    :loading="isLoading"
                                    style="cursor: pointer"
                                    class="secondary-btn gap-2"
                                    @click="store"
                                >
                                    <CustomIcon name="addIcon" />
                                    {{ $t("Create Comment") }}</loading-button
                                >
                            </div>
                        </div>
                    </div>
                    <div class="tickets">
                        <div
                            :class="[
                                'chat_box',
                                'comment',
                                user?.id !== comment.userId
                                    ? 'chat_box_right'
                                    : '',
                            ]"
                            v-for="comment in comments?.data ?? []"
                            :key="'comment-' + comment.id"
                        >
                            <div class="chat-box-left">
                                <div class="chat-user">
                                    <div class="flex items-center">
                                        <div
                                            class="chat-user-img"
                                            :style="{
                                                backgroundImage:
                                                    'url(' +
                                                    (userProfilePictures?.[
                                                        comment.userId
                                                    ]?.profile_image ?? '') +
                                                    ')',
                                            }"
                                        ></div>
                                        <div class="chat-user-name">
                                            <h3>
                                                {{
                                                    getInitials(
                                                        comment.userName ||
                                                            usernames[
                                                                comment.userId
                                                            ]
                                                    )
                                                }}
                                            </h3>
                                        </div>
                                        <div class="created-user">
                                            <p>
                                                @{{
                                                    comment.userName ||
                                                    usernames[comment.userId]
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            v-if="
                                                comment?.visibility ===
                                                'internal'
                                            "
                                            class="internal-tag"
                                        >
                                            <p>{{ $t("Internal") }}</p>
                                        </div>
                                    </div>
                                    <div
                                        v-if="
                                            user?.id === comment.userId &&
                                            comment.isArchived == 0 &&
                                            ticket.isArchived == 0 &&
                                            comment.isAllowedDelete
                                        "
                                        class="dropdown"
                                    >
                                        <div
                                            class="cursor-pointer chat-edit-bar mt-4"
                                        >
                                            <CustomIcon name="postbarIcon" />
                                        </div>
                                        <!-- <font-awesome-icon
                                            class="hidden cursor-pointer mt-5 mr-2"
                                            icon="fa-solid fa-ellipsis-vertical"
                                        ></font-awesome-icon> -->
                                        <div class="dropdown-content">
                                            <p
                                                @click="openEditModal(comment)"
                                                class="pt-2 pl-2 pb-2 cursor-pointer dropdown-action"
                                            >
                                                {{ $t("Edit") }}
                                            </p>
                                            <hr />
                                            <p
                                                @click="
                                                    openDeleteModal(comment.id)
                                                "
                                                class="pt-2 pl-2 pb-2 cursor-pointer dropdown-action"
                                            >
                                                {{ $t("Delete") }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-body">
                                    <div class="talktext">
                                        <div
                                            class="ql-editor"
                                            v-html="comment.text"
                                        ></div>
                                    </div>

                                    <div class="upload-gallary mt-5">
                                        <div
                                            v-for="(
                                                file, index
                                            ) in comment.attachment"
                                            :key="index"
                                            class="upload-box"
                                            :class="[index > 0 ? '' : '']"
                                        >
                                            <div
                                                class="upload-content flex items-center"
                                            >
                                                <font-awesome-icon
                                                    class="cursor-pointer mr-2"
                                                    icon="fa-solid fa-cloud-arrow-up"
                                                    @click="
                                                        downloadAttachment(
                                                            file.id,
                                                            file.viewable_name
                                                        )
                                                    "
                                                />
                                                <span>{{
                                                    file.viewable_name
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat-meta">
                                        <p>
                                            <span v-if="comment.time">
                                                {{ $t("Accounted Time") }}:
                                                {{ comment.time }}
                                            </span>
                                            <span v-else>
                                                {{ $t("Accounted Time") }}:
                                                {{ $t("Not Set") }}
                                            </span>

                                            {{
                                                comment.isArchived == 1
                                                    ? " - " + $t("Is Deleted")
                                                    : ""
                                            }}
                                        </p>
                                        <p>
                                            <!-- getTimeString(comment.createdAt) -->
                                            {{
                                                $dateFormatter(
                                                    comment.createdAt,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-box-right">
                                <div class="chat-user">
                                    <div
                                        class="flex items-center justify-end w-full"
                                    >
                                        <div
                                            v-if="
                                                comment?.visibility ===
                                                'internal'
                                            "
                                            class="internal-tag"
                                        >
                                            <p>{{ $t("Internal") }}</p>
                                        </div>
                                        <div class="created-user">
                                            <p>
                                                @{{
                                                    comment.userName ||
                                                    usernames[comment.userId]
                                                }}
                                            </p>
                                        </div>
                                        <div class="chat-user-name">
                                            <h3>
                                                {{
                                                    getInitials(
                                                        comment.userName ||
                                                            usernames[
                                                                comment.userId
                                                            ]
                                                    )
                                                }}
                                            </h3>
                                        </div>
                                        <div
                                            class="chat-user-img"
                                            :style="{
                                                backgroundImage:
                                                    'url(' +
                                                    (userProfilePictures?.[
                                                        comment.userId
                                                    ]?.profile_image ?? '') +
                                                    ')',
                                            }"
                                        ></div>
                                    </div>
                                </div>
                                <div class="chat-body">
                                    <div class="talktext">
                                        <div
                                            class="ql-editor"
                                            v-html="comment.text"
                                        ></div>
                                    </div>

                                    <div class="upload-gallary mt-5">
                                        <div
                                            v-for="(
                                                file, index
                                            ) in comment.attachment"
                                            :key="index"
                                            class="upload-box"
                                            :class="[index > 0 ? '' : '']"
                                        >
                                            <div
                                                class="upload-content flex items-center"
                                            >
                                                <font-awesome-icon
                                                    class="cursor-pointer mr-2"
                                                    icon="fa-solid fa-cloud-arrow-up"
                                                    @click="
                                                        downloadAttachment(
                                                            file.id,
                                                            file.viewable_name
                                                        )
                                                    "
                                                />
                                                <span>{{
                                                    file.viewable_name
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat-meta">
                                        <p>
                                            <span v-if="comment.time">
                                                {{ $t("Accounted Time") }}:
                                                {{ comment.time }}
                                            </span>
                                            <span v-else>
                                                {{ $t("Accounted Time") }}:
                                                {{ $t("Not Set") }}
                                            </span>

                                            {{
                                                comment.isArchived == 1
                                                    ? " - " + $t("Is Deleted")
                                                    : ""
                                            }}
                                        </p>
                                        <p>
                                            <!-- {{
                                                getTimeString(comment.createdAt)
                                            }} -->
                                            {{
                                                $dateFormatter(
                                                    comment.createdAt,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            ref="load"
                            v-show="perPage < comments?.total"
                            class="flex align-center justify-center my-2"
                        >
                            <div class="round-spinner mr-2 mt-1"></div>
                            <div>
                                <p>{{ $t("Loading more") }}....</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-if="
                    userPermissions?.includes('ticket.show') &&
                    userPermissions?.includes('ticket.edit')
                "
                class="lg:w-1/3 p-2 w-full"
            >
                <div class="card max-w-3xl bg-white rounded-md shadow p-5">
                    <div>
                        <div class="flex justify-between mb-8">
                            <h6 class="font-bold">
                                {{ companyDetails?.companyName ?? "" }}
                                <br />
                                {{ companyDetails?.address }}
                                <br />
                                {{ companyDetails?.code }}&nbsp;{{
                                    companyDetails?.city
                                }}
                                <br />
                                {{ companyDetails?.country }}
                                <br />
                                <span v-if="form?.reporter?.phone">
                                    Phone of {{ form?.reporter?.first_name }}:
                                    <br />
                                    {{ form?.reporter?.phone }}
                                </span>
                            </h6>
                            <div class="text-sm">
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("SLA Level") }}:</span
                                    ><span>{{
                                        form.amsId
                                            ? form.amsId?.attachment?.slaLevelId
                                                  ?.name
                                            : "-"
                                    }}</span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("SLA Time") }}:</span
                                    ><span>{{
                                        form.amsId
                                            ? form.amsId?.attachment
                                                  ?.slaServiceTimeId?.name
                                            : "-"
                                    }}</span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("Recieved Time") }}:</span
                                    ><span>{{
                                        $dateFormatter(
                                            ticket.createdAt,
                                            globalLanguage
                                        )
                                    }}</span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("Reaction Time") }}:</span
                                    ><span
                                        >{{
                                            convertDecimalToTime(
                                                form.amsId
                                                    ? form.amsId?.attachment
                                                          ?.slaLevelId?.[
                                                          reactionTimeEnums[
                                                              form.priority
                                                          ]
                                                      ]
                                                    : 0
                                            )
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 1fr 1fr"
                                >
                                    <span class="font-bold"
                                        >{{ $t("Deadline") }}:</span
                                    ><span
                                        >{{
                                            $dateFormatter(
                                                calculateDeadline(),
                                                globalLanguage
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-2 gap-4">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.softwareId"
                                    :key="form.softwareId"
                                    :options="softwares.data"
                                    @update:model-value="setAccounting"
                                    :multiple="false"
                                    :textLabel="$t('Software')"
                                    label="name"
                                    :trackBy="'id'"
                                    :moduleName="'softwares'"
                                    :taggable="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.type"
                                    :key="form.type"
                                    :label="$t('Type')"
                                    :error="errors?.type ?? ''"
                                >
                                    <option value="incident">
                                        {{ $t("Incident") }}
                                    </option>
                                    <option value="change">
                                        {{ $t("Change") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.area"
                                    :key="form.area"
                                    :label="$t('Area')"
                                    :error="errors?.area ?? ''"
                                >
                                    <option value="customer">
                                        {{ $t("Customer") }}
                                    </option>
                                    <option value="partner">
                                        {{ $t("Partners") }}
                                    </option>
                                    <option value="product">
                                        {{ $t("Products") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.priority"
                                    :key="form.priority"
                                    :required="true"
                                    :label="$t('Priority')"
                                    :error="errors?.priority ?? ''"
                                >
                                    <option value="low">
                                        1 {{ $t("low") }}
                                    </option>
                                    <option value="normal">
                                        2 {{ $t("normal") }}
                                    </option>
                                    <option value="high">
                                        3 {{ $t("high") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.visibility"
                                    :key="form.visibility"
                                    :required="true"
                                    :label="$t('Visibility')"
                                    :error="errors?.visibility ?? ''"
                                >
                                    <option value="internal">
                                        {{ $t("Internal Only") }}
                                    </option>
                                    <option value="public">
                                        {{ $t("Public") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.state"
                                    :key="form.state"
                                    :required="true"
                                    :label="$t('Status')"
                                    :error="errors?.state ?? ''"
                                >
                                    <option value="new">{{ $t("New") }}</option>
                                    <option value="open">
                                        {{ $t("Open") }}
                                    </option>
                                    <option value="waiting-for-response">
                                        {{ $t("Waiting For Response") }}
                                    </option>
                                    <option value="pending">
                                        {{ $t("Pending") }}
                                    </option>
                                    <option value="resolved">
                                        {{ $t("Resolved") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    @update:model-value="setSoftware"
                                    v-model="form.amsId"
                                    :key="form.amsId"
                                    :options="ams"
                                    @open="getAms()"
                                    :multiple="false"
                                    :textLabel="$t('Accounting')"
                                    label="amsNumber"
                                    trackBy="id"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-4 mb-6">
                        <div class="w-1/2">
                            <h3 class="card-title">Reporter</h3>
                            <div class="flex items-center mt-2">
                                <div
                                    class="flex justify-center items-center self-center mr-1 circle-image"
                                    :style="{
                                        backgroundImage:
                                            'url(' +
                                            (form.reporter?.profile_image ??
                                                '') +
                                            ')',
                                    }"
                                >
                                    <span v-if="!form.reporter?.profile_image">
                                        {{
                                            getInitials(
                                                (form?.reporter?.first_name ??
                                                    "") +
                                                    " " +
                                                    (form?.reporter
                                                        ?.last_name ?? "")
                                            )
                                        }}
                                    </span>
                                </div>
                                <!-- <p class="self-center">
                                    {{
                                        (form?.reporter?.first_name ?? "") +
                                        " " +
                                        (form?.reporter?.last_name ?? "")
                                    }}
                                </p> -->
                                <div class="form-group w-full">
                                    <MultiSelectInput
                                        class="w-full"
                                        @open="getUsersListing"
                                        v-model="form.reporter"
                                        :options="users"
                                        :multiple="false"
                                        :key="form.reporter"
                                        :error="errors.reporter"
                                        label="first_name"
                                        trackBy="id"
                                        moduleName="auth"
                                        :query="{ type: null }"
                                        :search-param-name="'search_string'"
                                    >
                                        <template #beforeList>
                                            <div
                                                class="grid p-2 gap-2"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p class="font-bold">
                                                    {{ $t("First Name") }}
                                                </p>
                                                <p class="font-bold">
                                                    {{ $t("Last Name") }}
                                                </p>
                                            </div>
                                            <hr />
                                        </template>
                                        <template #singleLabel="{ props }">
                                            <p>
                                                {{
                                                    props.option.first_name
                                                }}&nbsp;{{
                                                    props.option.last_name
                                                }}
                                            </p>
                                        </template>
                                        <template #option="{ props }">
                                            <div
                                                class="grid"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{
                                                        props.option.first_name
                                                    }}
                                                </p>
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.last_name }}
                                                </p>
                                            </div>
                                        </template>
                                    </MultiSelectInput>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <h3 class="card-title">{{ $t("Assignee") }}</h3>
                            <div class="flex items-center mt-2">
                                <div
                                    class="flex justify-center items-center self-center mr-1 circle-image"
                                    :style="{
                                        backgroundImage:
                                            'url(' +
                                            (ticket.assignee?.profile_image ??
                                                '') +
                                            ')',
                                    }"
                                >
                                    <span
                                        v-if="!ticket.assignee?.profile_image"
                                    >
                                        {{
                                            getInitials(
                                                (form?.assignee?.first_name ??
                                                    "") +
                                                    " " +
                                                    (form?.assignee
                                                        ?.last_name ?? "")
                                            )
                                        }}
                                    </span>
                                </div>
                                <div class="form-group w-full">
                                    <MultiSelectInput
                                        class="w-full"
                                        v-model="form.assignee"
                                        :key="form.assignee"
                                        :options="users"
                                        :multiple="false"
                                        @open="fetchUsers"
                                        :error="errors.assignee"
                                        label="first_name"
                                        trackBy="id"
                                        moduleName="auth"
                                        :search-param-name="'search_string'"
                                    >
                                        <template #beforeList>
                                            <div
                                                class="grid p-2 gap-2"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p class="font-bold">
                                                    {{ $t("First Name") }}
                                                </p>
                                                <p class="font-bold">
                                                    {{ $t("Last Name") }}
                                                </p>
                                            </div>
                                            <hr />
                                        </template>
                                        <template #singleLabel="{ props }">
                                            <p>
                                                {{
                                                    props.option.first_name
                                                }}&nbsp;{{
                                                    props.option.last_name
                                                }}
                                            </p>
                                        </template>
                                        <template #option="{ props }">
                                            <div
                                                class="grid"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{
                                                        props.option.first_name
                                                    }}
                                                </p>
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.last_name }}
                                                </p>
                                            </div>
                                        </template>
                                    </MultiSelectInput>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-sm">
                        <p>
                            {{ $t("Accounted Time") }}:&nbsp;{{
                                ticket?.totalAccountedTime
                            }}&nbsp;{{ $t("hrs") }}
                        </p>
                    </div>

                    <div
                        class="mt-4 p-2 chat"
                        v-if="comments?.data && comments?.data.length > 0"
                    >
                        <div
                            :class="[
                                'chat_box',
                                'comment',
                                user?.id !== comments?.data[comments?.data
                                                                ?.length - 1]?.userId
                                    ? 'chat_box_right'
                                    : '',
                            ]"
                        >
                            <div class="chat-box-left">
                                <div class="chat-user">
                                    <div class="flex items-center">
                                        <div
                                            class="chat-user-img"
                                            :style="{
                                                backgroundImage:
                                                    'url(' +
                                                    (userProfilePictures?.[
                                                        comments?.data[
                                                            comments?.data
                                                                ?.length - 1
                                                        ]?.userId
                                                    ]?.profile_image ?? '') +
                                                    ')',
                                            }"
                                        ></div>
                                        <div class="chat-user-name">
                                            <h3>
                                                {{
                                                    getInitials(
                                                        comments?.data[comments?.data
                                                                ?.length - 1]
                                                            ?.userName ||
                                                            usernames[
                                                                comments
                                                                    ?.data[comments?.data
                                                                ?.length - 1]
                                                                    ?.userId
                                                            ]
                                                    )
                                                }}
                                            </h3>
                                        </div>
                                        <div class="created-user">
                                            <p>
                                                @{{
                                                    comments?.data[comments?.data
                                                                ?.length - 1]
                                                        ?.userName ||
                                                    usernames[
                                                        comments?.data[comments?.data
                                                                ?.length - 1]
                                                            ?.userId
                                                    ]
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            v-if="
                                                comments?.data[comments?.data
                                                                ?.length - 1]
                                                    ?.visibility === 'internal'
                                            "
                                            class="internal-tag"
                                        >
                                            <p>{{ $t("Internal") }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-body">
                                    <div class="talktext">
                                        <div
                                            class="ql-editor"
                                            v-html="comments?.data[comments?.data
                                                                ?.length - 1]?.text"
                                        ></div>
                                    </div>

                                    <div class="upload-gallary mt-5">
                                        <div
                                            v-for="(file, index) in comments
                                                ?.data[comments?.data
                                                                ?.length - 1]?.attachment"
                                            :key="index"
                                            class="upload-box"
                                            :class="[index > 0 ? '' : '']"
                                        >
                                            <div
                                                class="upload-content flex items-center"
                                            >
                                                <font-awesome-icon
                                                    class="cursor-pointer mr-2"
                                                    icon="fa-solid fa-cloud-arrow-up"
                                                    @click="
                                                        downloadAttachment(
                                                            file.id,
                                                            file.viewable_name
                                                        )
                                                    "
                                                />
                                                <span>{{
                                                    file.viewable_name
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat-meta">
                                        <p>
                                            <span
                                                v-if="comments?.data[comments?.data
                                                                ?.length - 1]?.time"
                                            >
                                                {{ $t("Accounted Time") }}:
                                                {{ comments?.data[comments?.data
                                                                ?.length - 1]?.time }}
                                            </span>
                                            <span v-else>
                                                {{ $t("Accounted Time") }}:
                                                {{ $t("Not Set") }}
                                            </span>

                                            {{
                                                comments?.data[comments?.data
                                                                ?.length - 1]?.isArchived ==
                                                1
                                                    ? " - " + $t("Is Deleted")
                                                    : ""
                                            }}
                                        </p>
                                        <p>
                                            <!-- {{
                                                getTimeString(
                                                    comments?.data[0]?.createdAt
                                                )
                                            }} -->
                                            {{
                                                $dateFormatter(
                                                    comments?.data[comments?.data
                                                                ?.length - 1]
                                                        ?.createdAt,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-box-right">
                                <div class="chat-user">
                                    <div
                                        class="flex items-center justify-end w-full"
                                    >
                                        <div
                                            v-if="
                                                comments?.data[comments?.data
                                                                ?.length - 1]
                                                    ?.visibility === 'internal'
                                            "
                                            class="internal-tag"
                                        >
                                            <p>{{ $t("Internal") }}</p>
                                        </div>
                                        <div class="created-user">
                                            <p>
                                                @{{
                                                    comments?.data[comments?.data
                                                                ?.length - 1]
                                                        ?.userName ||
                                                    usernames[
                                                        comments?.data[comments?.data
                                                                ?.length - 1]
                                                            ?.userId
                                                    ]
                                                }}
                                            </p>
                                        </div>
                                        <div class="chat-user-name">
                                            <h3>
                                                {{
                                                    getInitials(
                                                        comments?.data[comments?.data
                                                                ?.length - 1]
                                                            ?.userName ||
                                                            usernames[
                                                                comments
                                                                    ?.data[comments?.data
                                                                ?.length - 1]
                                                                    ?.userId
                                                            ]
                                                    )
                                                }}
                                            </h3>
                                        </div>
                                        <div
                                            class="chat-user-img"
                                            :style="{
                                                backgroundImage:
                                                    'url(' +
                                                    (userProfilePictures?.[
                                                        comments?.data[comments?.data
                                                                ?.length - 1]
                                                            ?.userId
                                                    ]?.profile_image ?? '') +
                                                    ')',
                                            }"
                                        ></div>
                                    </div>
                                </div>
                                <div class="chat-body">
                                    <div class="talktext">
                                        <div
                                            class="ql-editor"
                                            v-html="comments?.data[comments?.data
                                                                ?.length - 1]?.text"
                                        ></div>
                                    </div>

                                    <div class="upload-gallary mt-5">
                                        <div
                                            v-for="(file, index) in comments
                                                ?.data[comments?.data
                                                                ?.length - 1]?.attachment"
                                            :key="index"
                                            class="upload-box"
                                            :class="[index > 0 ? '' : '']"
                                        >
                                            <div
                                                class="upload-content flex items-center"
                                            >
                                                <font-awesome-icon
                                                    class="cursor-pointer mr-2"
                                                    icon="fa-solid fa-cloud-arrow-up"
                                                    @click="
                                                        downloadAttachment(
                                                            file.id,
                                                            file.viewable_name
                                                        )
                                                    "
                                                />
                                                <span>{{
                                                    file.viewable_name
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat-meta">
                                        <p>
                                            <span
                                                v-if="comments?.data[comments?.data
                                                                ?.length - 1]?.time"
                                            >
                                                {{ $t("Accounted Time") }}:
                                                {{ comments?.data[comments?.data
                                                                ?.length - 1].time }}
                                            </span>
                                            <span v-else>
                                                {{ $t("Accounted Time") }}:
                                                {{ $t("Not Set") }}
                                            </span>
                                            {{
                                                comments?.data[comments?.data
                                                                ?.length - 1]?.isArchived ==
                                                1
                                                    ? " - " + $t("Is Deleted")
                                                    : ""
                                            }}
                                        </p>
                                        <p>
                                            <!-- {{
                                                getTimeString(
                                                    comments?.data[0]?.createdAt
                                                )
                                            }} -->
                                            {{
                                                $dateFormatter(
                                                    comments?.data[comments?.data
                                                                ?.length - 1]
                                                        ?.createdAt,
                                                    globalLanguage
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-10">
            <loading-button
                :loading="isLoading"
                style="cursor: pointer"
                class="secondary-btn self-center"
                @click="update"
                >{{ $t("Update Ticket") }}</loading-button
            >
        </div>
    </div>
    <Modal
        title="Delete Comment"
        v-if="toggleDeleteModal"
        @toggleModal="toggleDeleteModal = $event"
        width="50%"
    >
        <template #body>
            <div class="flex ml-6 mr-6 border mb-3 p-3">
                <p>{{ $t("Are you sure you want to delete this comment?") }}</p>
            </div>
        </template>
        <template #footer>
            <button @click="destroy" type="button" class="secondary-btn">
                {{ $t("Delete") }}
            </button>
            <button
                @click="toggleDeleteModal = false"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
    <Modal
        title="Edit Comment"
        v-if="toggleEditModal"
        @toggleModal="toggleEditModal = $event"
        width="50%"
    >
        <template #body>
            <div class="ml-6 mr-6 border mb-3 p-3">
                <QuillTextEditor
                    class="pb-8 pr-6 w-full lg:w-full mt-2"
                    :content="ticketForm.text"
                    :content-type="'html'"
                    :required="true"
                    :error="errors.text"
                    @delta="ticketForm.text = $event"
                    :label="$t('Text')"
                />
                <div class="pb-8 pr-6 w-full lg:w-full">
                    <div class="form-group">
                        <number-input
                            :roundNearQuarter="true"
                            :forceLeftBound="true"
                            :showPrefix="false"
                            v-model="ticketForm.time"
                            :required="true"
                            :canBeNull="true"
                            :label="$t('Accounted Time')"
                            :error="errors.time ?? ''"
                        />
                    </div>
                </div>
                <div class="pb-8 pr-6 w-full lg:w-full">
                    <div class="form-group">
                        <select-input
                            v-model="ticketForm.visibility"
                            :label="$t('Visibility')"
                            :error="errors?.visibility ?? ''"
                            :required="true"
                        >
                            <option value="internal">
                                {{ $t("Internal Only") }}
                            </option>
                            <option value="public">{{ $t("Public") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="pb-8 pr-6 w-full lg:w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            @open="getUsersListing"
                            v-model="ticketForm.informedUsers"
                            :options="users"
                            :text-label="$t('Users To Inform')"
                            :multiple="true"
                            :key="ticketForm.informedUsers"
                            :error="errors.informedUsers"
                            label="first_name"
                            trackBy="id"
                            moduleName="auth"
                            :query="{ type: null }"
                            :search-param-name="'search_string'"
                        >
                            <template #beforeList>
                                <div
                                    class="grid p-2 gap-2"
                                    style="grid-template-columns: 50% 50%"
                                >
                                    <p class="font-bold">
                                        {{ $t("First Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Last Name") }}
                                    </p>
                                </div>
                                <hr />
                            </template>
                            <template #singleLabel="{ props }">
                                <p>
                                    {{ props.option.first_name }}&nbsp;{{
                                        props.option.last_name
                                    }}
                                </p>
                            </template>
                            <template #option="{ props }">
                                <div
                                    class="grid"
                                    style="grid-template-columns: 50% 50%"
                                >
                                    <p class="overflow-text-users-table">
                                        {{ props.option.first_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.last_name }}
                                    </p>
                                </div>
                            </template>
                        </MultiSelectInput>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="updateComment" type="button" class="secondary-btn">
                {{ $t("Update") }}
            </button>
            <button
                @click="toggleEditModal = false"
                type="button"
                class="primary-btn ml-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
    <ImageModal
        v-if="toggleImageModal"
        @toggleModal="
            toggleImageModal = $event;
            imageSrc = '';
        "
        :src="imageSrc"
    />
</template>

<script>
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import Modal from "../../Components/EditModal.vue";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import ImageModal from "@/Components/ImageModal.vue";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin.js";
import FileInputs from "../../Components/FileInputs.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    mixins: [userProfilePicturesMixin],
    computed: {
        ...mapGetters(["errors", "isLoading", "pusher"]),
        ...mapGetters("auth", [
            "user",
            "userPermissions",
            "users",
            "userProfilePictures",
        ]),
        ...mapGetters("softwares", ["softwares"]),
    },
    components: {
        MultiSelectInput,
        QuillTextEditor,
        Modal,
        LoadingButton,
        TextAreaInput,
        TextInput,
        SelectInput,
        NumberInput,
        ImageModal,
        FileInputs,
        PageHeader,
    },
    watch: {
        "ticketForm.time"(val) {
            this.errors.time = "";
        },
        "ticketForm.text"(val) {
            this.errors.text = "";
        },
    },
    data() {
        return {
            informedUsers: [],
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Support"),
                    to: "/tickets",
                },
                {
                    text: this.$t("Tickets"),
                    to: "/tickets?page=" + this.$route.query.page,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            ticketId: "",
            imageSrc: "",
            toggleImageModal: false,
            reactionTimeEnums: {
                low: "reactionTimeLow",
                normal: "reactionTimeHigh",
                high: "reactionTimeLowUrgent",
            },
            companyDetails: {},
            shouldShow: false,
            observer: new IntersectionObserver(this.infiniteScroll),
            perPage: 10,
            toggleEditModal: false,
            toggleDeleteModal: false,
            selectedCommentId: "",
            companies: [],
            ticketEditToggle: false,
            ticketForm: {
                text: "",
                time: "",
                ticketId: this.$route.params.id,
                visibility: "",
                informedUsers: [],
            },
            form: {
                title: "",
                state: "",
                priority: "",
                contactType: "",
                companyId: "",
                assignee: "",
                type: "",
                area: "",
                amsId: "",
                softwareId: "",
            },
            commentFiles: [],
            ticket: {},
            comments: {
                data: [],
            },
            isReadonly: false,
            usernames: [],
            ams: [],
        };
    },
    methods: {
        /**
         * fetch users listing is not already fetched
         */
        fetchUsers() {
            if (!this.users?.length) {
                this.$store.dispatch("auth/list");
            }
        },

        async getUsersListing() {
            try {
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * converts decimal hours to hours and minutes string
         * @param {decimalHours} time to be converted
         * @return converted string
         */
        convertDecimalToTime(decimalHours) {
            // Get the integer and fractional parts of the decimal hours
            const hours = Math.floor(decimalHours);
            const decimalPart = decimalHours - hours;

            // Convert the fractional part to minutes
            const minutes = Math.round(decimalPart * 60);

            // Create a human-readable time string
            let timeString = "";
            timeString += hours + " hour";
            if (hours > 1) {
                timeString += "s";
            }

            timeString += " ";

            timeString += minutes + " minute";
            if (minutes > 1) {
                timeString += "s";
            }

            return timeString;
        },
        /**
         * calculates the deadline date based on the from and to times from SLA service time for the linked attachment and the created at date
         */
        calculateDeadline() {
            let deadLine = "";
            const fromDate = new Date(this.ticket.createdAt); // set to the created at date
            const fromTokens = (
                this.form.amsId?.attachment?.slaServiceTimeId?.from ?? ""
            ).split(":"); // get the hour and minute but spliting with ":"
            // set the hour and minute
            fromDate.setHours(fromTokens?.[0] ?? 0);
            fromDate.setMinutes(fromTokens?.[1] ?? 0);
            fromDate.setSeconds(fromTokens?.[2] ?? 0);
            const toDate = new Date(this.ticket.createdAt);
            const toTokens = (
                this.form.amsId?.attachment?.slaServiceTimeId?.to ?? ""
            ).split(":"); // get the hour and minute but spliting with ":"
            // set the hour and minute
            toDate.setHours(toTokens?.[0] ?? 0);
            toDate.setMinutes(toTokens?.[1] ?? 0);
            toDate.setSeconds(toTokens?.[2] ?? 0);
            // get reaction time from the sla level
            const reactionTime =
                this.form.amsId?.attachment?.slaLevelId?.[
                    this.reactionTimeEnums[this.form.priority]
                ] ?? 0;
            if (new Date(this.ticket.createdAt) < fromDate) {
                const fromPlusReactionTime = this.addHours(
                    fromDate,
                    reactionTime
                );
                if (new Date(fromPlusReactionTime) <= toDate) {
                    deadLine = fromPlusReactionTime;
                } else {
                    const remainingTimeNextDay =
                        +reactionTime -
                        +this.getHoursDiffBetweenDates(fromDate, toDate);
                    deadLine = this.addHours(
                        fromDate,
                        24 + +remainingTimeNextDay
                    );
                }
            } else {
                deadLine = this.addHours(
                    new Date(this.ticket.createdAt),
                    +reactionTime
                );
            }
            return deadLine;
        },
        /**
         * calculates the difference in hours between two dates
         * @param {dateInitial} start date
         * @param {dateFinal} end date
         */
        getHoursDiffBetweenDates(dateInitial, dateFinal) {
            return (dateFinal - dateInitial) / (1000 * 3600);
        },
        /**
         * adds hours to a date
         * @param {date} the date
         * @param {hours} hours to be added
         */
        addHours(date, hours) {
            try {
                const d = new Date(date);
                d.setHours(d.getHours() + +hours);
                return (
                    d.toISOString().slice(0, 10) +
                    " " +
                    d.toTimeString().slice(0, 8)
                );
            } catch (e) {
                return date;
            }
        },
        async setSoftware() {
            await this.$nextTick();
            this.form.softwareId = this.form.amsId?.attachment?.software ?? "";
        },
        async setAccounting(event) {
            await this.getAms();
            this.ams = this.ams.filter((item) => item.softwareId == event.id);
            this.form.amsId = this.ams ? this.ams[0] : "";
        },
        async getAms() {
            await this.$nextTick();
            try {
                const response = await this.$store.dispatch(
                    "ams/getByCustomerAndAttachment",
                    this.form.companyId
                );
                this.ams = response?.data?.data ?? [];
            } catch (e) {
                console.log(e);
            }
        },
        isJSON(str) {
            try {
                JSON.parse(str);
                return true;
            } catch (err) {
                return false;
            }
        },
        async infiniteScroll([{ isIntersecting, target }]) {
            if (isIntersecting) {
                const ul = target.offsetParent;
                const scrollTop = target.offsetParent.scrollTop;
                this.perPage += 10;
                await this.fetchTicketComments();
                this.getUsernames();
                await this.$nextTick();
                ul.scrollTop = scrollTop;
            }
        },
        getUsernames() {
            let username = [];
            this.comments?.data.forEach(async (comment) => {
                if (!username.includes(comment.userId)) {
                    username.push(comment.userId);
                    let response = {};
                    if (comment.userId && comment.userId !== "n.a.") {
                        response = await this.$store.dispatch("auth/show", {
                            id: comment.userId,
                        });
                    }
                    if (
                        !response?.data?.first_name &&
                        !response?.data?.last_name
                    ) {
                        this.usernames[comment.userId] = "User not found";
                    } else
                        this.usernames[comment.userId] =
                            (response?.data?.first_name ?? "") +
                            " " +
                            (response?.data?.last_name ?? "");
                }
            });
        },
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
        getTimeString(time) {
            return (
                this.$dateFormatter(time?.substr(0, 10), this.globalLanguage) +
                " " +
                time?.substr(11, 8)
            );
        },
        async openEditModal(comment) {
            try {
                this.$store.commit("showLoader", true);
                this.selectedCommentId = comment.id;
                this.ticketForm.text = comment.text;
                this.ticketForm.time = comment.time;
                this.ticketForm.ticketId = this.ticketId;
                this.ticketForm.visibility = comment.visibility ?? "";
                if (!!comment.informedUsers?.length) {
                    const response = await this.$store.dispatch("auth/show", {
                        id: comment.informedUsers.map((user) => user.userId),
                    });
                    this.ticketForm.informedUsers = response?.data ?? [];
                }
                this.toggleEditModal = true;
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        openDeleteModal(id) {
            this.selectedCommentId = id;
            this.toggleDeleteModal = true;
        },
        async updateComment() {
            try {
                this.$store.commit("isLoading", true);
                const ticketCommentResponse = await this.$store.dispatch(
                    "ticketComments/update",
                    {
                        id: this.selectedCommentId,
                        data: {
                            ...this.ticketForm,
                            ticketId: this.$route.params.id,
                            userId: this.user.id,
                            userType: "employee",
                            informedUsers: this.ticketForm.informedUsers.map(
                                (user) => {
                                    return {
                                        userId: user.id,
                                    };
                                }
                            ),
                        },
                    }
                );
                this.toggleEditModal = false;
                this.pusher.sendMessage(
                    JSON.stringify({
                        ticket: ticketCommentResponse?.data?.ticket ?? null,
                        action: "CommentUpdated",
                        userName:
                            this.user?.first_name + " " + this.user?.last_name,
                    }),
                    [
                        ...(ticketCommentResponse?.data?.ticketUsers ?? []),
                        ...this.ticketForm.informedUsers.map((user) => user.id),
                    ],
                    null
                );
                this.shouldShow = false;
                await this.fetchTicketComments();
                this.selectedCommentId = "";
                this.ticketForm = {
                    time: "",
                    text: "",
                    ticketId: this.ticketId,
                };
            } catch (e) {
                console.log(e);
            } finally {
                this.shouldShow = true;
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("tickets/update", {
                    id: this.ticketId,
                    data: {
                        ...this.form,
                        // userId: this.user.id,
                        userId: this.form.reporter?.id,
                        assignee: this.form.assignee?.id,
                        amsId: this.form.amsId?.id,
                        softwareId: this.form.softwareId?.id,
                        userType: "employee",
                    },
                });
                this.refresh();
                await this.$store.dispatch("tickets/list");
                this.$store.dispatch("tickets/openTicketsCount");
            } catch (e) {}
        },
        /**
         * fetches ticket comments listing
         */
        fetchTicketComments() {
            return new Promise(async (resolve, reject) => {
                try {
                    const responseComments = await this.$store.dispatch(
                        "ticketComments/list",
                        {
                            id: this.ticketId,
                            queryParams: {
                                perPage: this.perPage,
                            },
                        }
                    );
                    this.comments = responseComments?.data?.data ?? [];
                    this.getUserProfilePictures(
                        this.comments?.data ?? [],
                        "userId"
                    );
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                const ticketCommentResponse = await this.$store.dispatch(
                    "ticketComments/create",
                    {
                        ...this.ticketForm,
                        ticketId: this.ticketId,
                        userId: this.user.id,
                        attachment: this.commentFiles,
                        userType: "employee",
                        informedUsers: this.informedUsers.map((user) => {
                            return {
                                userId: user.id,
                            };
                        }),
                    }
                );
                this.commentFiles = [];
                await this.fetchTicketComments();
                this.getUsernames();
                const response = await this.$store.dispatch(
                    "tickets/show",
                    this.$route.params.id
                );
                this.ticket = response?.data?.tickets ?? {};
                this.shouldShow = false;
                await this.$nextTick();
                try {
                    await this.$store.dispatch("ticketComments/sendMail", {
                        id: ticketCommentResponse?.data?.id,
                        data: {
                            reporter: this.form?.reporter?.email,
                            assignee: this.form?.assignee?.email,
                            isAssigneeEmployee:
                                (this.form.assignee?.types?.["employee"] ??
                                    0) == 1,
                            isReporterEmployee:
                                (this.form.reporter?.types?.["employee"] ??
                                    0) == 1,
                            informedUsers: this.informedUsers
                                .filter((user) =>
                                    this.ticketForm?.visibility === "internal"
                                        ? user.types?.["employee"] ?? 0 == 1
                                        : true
                                )
                                .map((user) => user.email),
                        },
                    });
                } catch (e) {
                    console.log(e);
                } finally {
                    this.ticketForm = {
                        text: "",
                        time: "",
                        ticketId: this.ticketId,
                    };
                }
                this.pusher.sendMessage(
                    JSON.stringify({
                        ticket: ticketCommentResponse?.data?.ticket ?? null,
                        action: "CommentCreated",
                        userName:
                            this.user?.first_name + " " + this.user?.last_name,
                    }),
                    [
                        ...(ticketCommentResponse?.data?.ticketUsers ?? []),
                        ...this.informedUsers.map((user) => user.id),
                    ],
                    null
                );
                this.informedUsers.splice(0, this.informedUsers.length);
            } catch (err) {
            } finally {
                this.shouldShow = true;
            }
        },
        async destroy() {
            try {
                await this.$store.dispatch(
                    "ticketComments/destroy",
                    this.selectedCommentId
                );
                this.toggleDeleteModal = false;
                this.selectedCommentId = "";
                await this.refresh();
            } catch (e) {}
        },
        addFiles(event) {
            const files = event.target.files;
            files.forEach((file) => {
                this.processFile(file);
            });
        },
        processFile(file) {
            let base64Content = "";

            var reader = new FileReader();
            reader.onload = (event) => {
                const fileName = file.name;
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const fileExtension = fileName
                    .substring(fileName.lastIndexOf(".") + 1)
                    .toLowerCase();

                const dataUri = event.target.result;
                const parts = dataUri.split(",");
                if (parts.length === 2) {
                    base64Content = parts[1];
                    let file = {
                        name: fileName,
                        type: fileExtension,
                        size: `${fileSizeMB} MB`,
                        base64: base64Content,
                    };
                    this.commentFiles.push(file);
                }
            };
            reader.readAsDataURL(file);
        },
        removeFile(index) {
            this.commentFiles.splice(index, 1);
        },
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "tickets/show",
                    this.$route.params.id
                );
                this.ticket = response?.data?.tickets ?? {};
                this.ticketId = response?.data?.tickets?.id ?? "";
                document.title = "Edit Ticket " + this.ticket.ticketNumber;
                this.companyDetails =
                    response?.data?.tickets?.companyDetails ?? {};
                await this.fetchTicketComments();
                this.getUsernames();
                if (this.$refs.load) this.observer?.observe(this.$refs.load);
            } catch (e) {
                console.log(e);
            } finally {
                if (this.ticket?.userId) {
                    try {
                        const response = await this.$store.dispatch(
                            "auth/show",
                            {
                                id: this.ticket?.userId,
                            }
                        );
                        this.form.reporter = response?.data ?? "";
                    } catch (e) {
                        console.log(e);
                    }
                }
            }
        },
        async downloadAttachment(id, viewableName) {
            try {
                // check if the file is an image
                const imageFileRegex = /\.(jpg|jpeg|png|gif|bmp|svg)$/i;
                if (imageFileRegex.test(viewableName)) {
                    const response = await this.$store.dispatch(
                        "tickets/downloadTicketAttachmentBase64",
                        {
                            id: id,
                            queryParams: { name: viewableName },
                        }
                    );
                    var reader = new FileReader();
                    reader.readAsDataURL(response?.data);
                    reader.onerror = (err) => {
                        console.log(err);
                    };
                    reader.onloadend = () => {
                        //convert to base64 and set to imageSrc and toggle the image modal to view the image
                        this.imageSrc = reader.result;
                        this.toggleImageModal = true;
                    };
                } else {
                    this.$store.dispatch("tickets/downloadTicketAttachment", {
                        id: id,
                        queryParams: { name: viewableName },
                    });
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            await this.$store.dispatch("softwares/list");
            await this.refresh();
            this.$store
                .dispatch("tickets/show", this.$route.params.id)
                .then((res) => {
                    this.form = {
                        ...this.form,
                        title: res?.data?.tickets?.title ?? "",
                        state: res?.data?.tickets?.state ?? "",
                        priority: res?.data?.tickets?.priority ?? "",
                        area: res?.data?.tickets?.area ?? "",
                        contactType: res?.data?.tickets?.contactType ?? "",
                        companyId: res?.data?.tickets?.companyId ?? "",
                        visibility: res?.data?.tickets?.visibility ?? "",
                        assignee: res?.data?.tickets?.assignee,
                        text: res?.data?.tickets?.text ?? "",
                        type: res?.data?.tickets?.type ?? "",
                        amsId: res?.data?.tickets?.ams ?? "",
                        softwareId: res?.data?.tickets?.software ?? "",
                    };
                })
                .finally(async () => {
                    try {
                        if (!!this.form.assignee) {
                            const response = await this.$store.dispatch(
                                "auth/show",
                                {
                                    id: this.form.assignee,
                                }
                            );
                            this.form.assignee = response?.data ?? "";
                        }
                    } catch (e) {
                        console.log(e);
                    }
                });

            this.$store
                .dispatch("companies/list", {
                    perPage: 0,
                    page: 1,
                })
                .then((res) => {
                    this.companies = res?.data?.data ?? [];
                });
        } catch (e) {
        } finally {
            this.shouldShow = true;
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style scoped>
.circle-image {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    min-height: 32px;
    min-width: 32px;
    border-radius: 50%;
    background-color: #2957a4;
    color: white;
    background-repeat: no-repeat;
    background-size: cover;
    min-height: 32px;
    min-width: 32px;
    border-radius: 50%;
    background-color: #2957a4;
    color: white;
}

.ql-container.ql-snow {
    padding-bottom: 45px;
}

.dropdown-action:hover {
    background-color: #2957a4;
    color: white;
}

.dropdown {
    position: relative;
    display: inline-block;
    margin-top: -10px;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    right: 0;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.comment {
    position: relative;
}

.comment:hover svg {
    display: block !important;
}

.chat_box {
    border-bottom: 1px solid #e7eaed;
    padding: 15px 0px;
}

.chat::-webkit-scrollbar {
    width: 5px;
    height: 3px;
}

.chat::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}

.chat::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
    border-radius: 5px;
}

.talk-bubble {
    margin: 20px;
    display: inline-block;
    position: relative;
    /* width: 200px; */
    height: auto;
    background-color: white;
}

.round {
    border-radius: 30px;
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
}

.tri-right.border.left-top:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -40px;
    right: auto;
    top: -8px;
    bottom: auto;
    border: 32px solid;
    border-color: #666 transparent transparent transparent;
}

.tri-right.left-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -22px;
    right: auto;
    top: 0px;
    bottom: auto;
    border: 22px solid;
    border-color: white transparent transparent transparent;
}

/* Right triangle, left side slightly down */
.tri-right.border.left-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -22px;
    right: auto;
    top: 30px;
    bottom: auto;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.left-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -12px;
    right: auto;
    top: 22px;
    bottom: auto;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/*Right triangle, placed bottom left side slightly in*/
.tri-right.border.btm-left:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -8px;
    right: auto;
    top: auto;
    bottom: -40px;
    border: 32px solid;
    border-color: transparent transparent transparent #666;
}

.tri-right.btm-left:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 0px;
    right: auto;
    top: auto;
    bottom: -20px;
    border: 22px solid;
    border-color: transparent transparent transparent white;
}

/*Right triangle, placed bottom left side slightly in*/
.tri-right.border.btm-left-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 30px;
    right: auto;
    top: auto;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 transparent transparent #666;
}

.tri-right.btm-left-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 38px;
    right: auto;
    top: auto;
    bottom: -20px;
    border: 12px solid;
    border-color: white transparent transparent white;
}

/*Right triangle, placed bottom right side slightly in*/
.tri-right.border.btm-right-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 30px;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.btm-right-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 38px;
    bottom: -20px;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/*
	left: -8px;
  right: auto;
  top: auto;
	bottom: -40px;
	border: 32px solid;
	border-color: transparent transparent transparent #666;
	left: 0px;
  right: auto;
  top: auto;
	bottom: -20px;
	border: 22px solid;
	border-color: transparent transparent transparent white;

/*Right triangle, placed bottom right side slightly in*/
.tri-right.border.btm-right:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -8px;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.btm-right:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 0px;
    bottom: -20px;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/* Right triangle, right side slightly down*/
.tri-right.border.right-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -40px;
    top: 30px;
    bottom: auto;
    border: 20px solid;
    border-color: #666 transparent transparent #666;
}

.tri-right.right-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -12px;
    top: 22px;
    bottom: auto;
    border: 12px solid;
    border-color: white transparent transparent white;
}

/* Right triangle placed top right flush. */
.tri-right.border.right-top:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -40px;
    top: -8px;
    bottom: auto;
    border: 32px solid;
    border-color: #666 transparent transparent transparent;
}

.tri-right.right-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -20px;
    top: 0px;
    bottom: auto;
    border: 20px solid;
    border-color: white transparent transparent transparent;
}

/* talk bubble contents */

.is-archived {
    text-decoration: line-through;
}

:deep(.multiselect__single) {
    font-size: inherit;
}

:deep(.multiselect__tags) {
    min-height: 20px;
}
</style>
