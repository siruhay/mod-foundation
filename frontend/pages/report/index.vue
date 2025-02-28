<template>
	<page-blank page-name="foundation-report" page-key="report" show-sidenav>
		<template
			v-slot:sidenav="{ combos: { subdistricts, types }, record, theme }"
		>
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-select
							:items="types"
							label="Type"
							v-model="record.type"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="subdistricts"
							label="Kecamatan"
							v-model="record.subdistrict"
							hide-details
						></v-select>
					</v-col>
				</v-row>
			</v-card-text>

			<v-divider class="my-2"></v-divider>

			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-btn
							:color="theme"
							variant="flat"
							block
							@click="getReportPreview(record)"
							>PREVIEW
						</v-btn>
					</v-col>
				</v-row>
			</v-card-text>
		</template>

		<template v-slot:default>
			<div v-html="preview"></div>
		</template>
	</page-blank>
</template>

<script>
export default {
	name: "foundation-report",

	data: () => ({
		preview: null,
		type: null,
	}),

	methods: {
		getReportPreview: function (record) {
			this.$http(`foundation/api/report`, {
				method: "GET",
				params: record,
			}).then((response) => {
				this.preview = response;
			});
		},
	},
};
</script>
