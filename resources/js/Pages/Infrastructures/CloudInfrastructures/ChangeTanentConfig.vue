<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ $t("Change Tanent Config Of") }}
                    {{ selectTenetForConfigChange.partnerName}}
                    {{ selectTenetForConfigChange.companyName}}
                </h3>
            </div>
            <div class="card-body">
                    <div class="flex item-center">
                        <div class="min-w-[250px] bg-[#2A2A2A] p-2">
                            <div class="flex items-center">
                                <icon name="rightArrow" />
                                <p class="text-[#ffffff] text-sm">
                                    {{ directoryStructure.name }}
                                </p>
                            </div>
                            <DirectoryStructure
                                :directoryStruct="directoryStructure"
                            />
                        </div>
                        <MonacoEditor
                            @contentChange="setConfig($event)"
                            :readOnly="false"
                            :codeString="fileConfigData.content"
                            :height="'70vh'"
                            :language="
                                fileConfigData?.name?.split('.').pop() === 'xml'
                                    ? 'xml'
                                    : fileConfigData?.name
                                          ?.split('.')
                                          ?.pop() === 'json'
                                    ? 'json'
                                    : fileConfigData.name?.split('.')?.pop() ===
                                      'yaml'
                                    ? 'yaml'
                                    : ''
                            "
                        />
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import Modal from "@/Components/EditModal.vue";
import MonacoEditor from "@/Components/MonacoEditor.vue";
import DirectoryStructure from "./Components/DirectoryStructure.vue";
import icon from "@/Components/Icon.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("serverPools", [
            "serverPools",
            "serverPoolId",
            "fileConfigData",
            "selectTenetForConfigChange",
        ]),
        ...mapGetters("companies", ["partners", "companies"]),
        ...mapGetters("auth", ["user"]),
    },
    async mounted() {
        try {
        } catch (e) {
            console.log(e);
        }
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Systems"),
                    to: `/infrastructures/cloud-infrastructures?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Update"),
                    to: `/infrastructures/cloud-infrastructures/${this.$route.params.id}/edit?page=1`,
                },
                {
                    text: this.$t("Change Tanent Config"),
                    active: true,
                },
            ],
            directoryStructure: {
                id: "32-234123",
                name: "Project Name",
                directories: [
                    {
                        id: "232-53454",
                        name: "filename1.xml",
                        content: `<note>
	<to>Tove</to>
	<from>Jani</from>
	<heading>Reminder</heading>
	<body>Don't
  forget me this weekend!</body>
</note>
`,
                    },
                    {
                        id: "232-53454",
                        name: "filename2.json",
                        content: `{
                                "id":8,
                                "tenant_name":"ALTUS_renewables_GmbH",
                                "customer_id":"1876d471-40ab-553f-197a-409a103decba",
                                "partner_id":"18b73694-1e4a-cee3-015a-90b0880f6384",
                                "cloud_tenant_repositories":[
                                    {
                                        "id":6,
                                        "cloud_tenant_id":8,
                                        "database_size":34,
                                        "data_size":345
                                    },
                                    {
                                        "id":7,
                                        "cloud_tenant_id":8,
                                        "database_size":56,
                                        "data_size":87,
                                        "name":"dsdf",
                                        "created_at":"2023-12-06T18:31:19.000000Z",
                                        "updated_at":"2023-12-06T18:31:19.000000Z"
                                    },
                                    {
                                        "id":6,
                                        "cloud_tenant_id":8,
                                        "database_size":34,
                                        "data_size":345
                                    },
                                    {
                                        "id":7,
                                        "cloud_tenant_id":8,
                                        "database_size":56,
                                        "data_size":87,
                                        "name":"dsdf",
                                        "created_at":"2023-12-06T18:31:19.000000Z",
                                        "updated_at":"2023-12-06T18:31:19.000000Z"
                                    }
                                ]
                            }`,
                    },
                    {
                        id: "232-53454",
                        name: "filename3.xml",
                        content: `<breakfast_menu>
	<food>
		<name>Belgian Waffles</name>
		<price>$5.95</price>
		<description>Two of our famous Belgian Waffles with plenty of real maple syrup</description>
		<calories>650</calories>
	</food>
	<food>
		<name>Strawberry Belgian Waffles</name>
		<price>$7.95</price>
		<description>Light Belgian waffles covered with strawberries and whipped cream</description>
		<calories>900</calories>
	</food>
	<food>
		<name>Berry-Berry Belgian Waffles</name>
		<price>$8.95</price>
		<description>Light Belgian waffles covered with an assortment of fresh berries and whipped cream</description>
		<calories>900</calories>
	</food>
	<food>
		<name>French Toast</name>
		<price>$4.50</price>
		<description>Thick slices made from our homemade sourdough bread</description>
		<calories>600</calories>
	</food>
	<food>
		<name>Homestyle Breakfast</name>
		<price>$6.95</price>
		<description>Two eggs, bacon or sausage, toast, and our ever-popular hash browns</description>
		<calories>950</calories>
	</food>
</breakfast_menu>`,
                    },
                    {
                        id: "32-234123",
                        name: "DIR TWO",
                        directories: [
                            {
                                id: "3232-324",
                                name: "filename4.yaml",
                                content: `---
# <- yaml supports comments, json does not
# did you know you can embed json in yaml?
# try uncommenting the next line
# { foo: 'bar' }

json:
  - rigid
  - better for data interchange
yaml: 
  - slim and flexible
  - better for configuration
object:
	key: value
  array:
    - null_value:
    - boolean: true
    - integer: 1
    - alias: &example aliases are like variables
    - alias: *example
paragraph: >
   Blank lines denote

   paragraph breaks
content: |-
   Or we
   can auto
   convert line breaks
   to save space
alias: &foo
  bar: baz
alias_reuse: *foo `,
                            },
                            {
                                id: "3232-324",
                                name: "DIR THREE",
                                directories: [
                                    {
                                        id: "3232-324",
                                        name: "filename5.xml",
                                        content: `<note>
	<to>Tove</to>
	<from>Jani</from>
	<heading>Reminder</heading>
	<body>Don't forget me this weekend!</body>
</note>`,
                                    },
                                    {
                                        id: "3232-324",
                                        name: "filename6.json",
                                        content: `{
                                        "id":8,
                                        "tenant_name":"ALTUS_renewables_GmbH",
                                        "customer_id":"1876d471-40ab-553f-197a-409a103decba",
                                        "partner_id":"18b73694-1e4a-cee3-015a-90b0880f6384",
                                        "cloud_tenant_repositories":[
                                            {
                                                "id":6,
                                                "cloud_tenant_id":8,
                                                "database_size":34,
                                                "data_size":345
                                            }
                                        ]
                                    }`,
                                    },
                                ],
                            },
                        ],
                    },
                ],
            },
            config: {},
            codeString: `{
			"id":"232-53454",
			"name":"filename.xml",
			"content": " <html>some content of a file</html> can be any file: json, xml, ..."
		    }`,
        };
    },
    components: {
        TextInput,
        Modal,
        MultiSelectInput,
        MonacoEditor,
        DirectoryStructure,
        icon,
        PageHeader,
    },
    methods: {
        setConfig(event) {
            this.config = event ?? this.fileConfigData.content;
        },
    },
};
</script>

<style scoped></style>
