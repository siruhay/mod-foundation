<template>
	<form-edit with-helpdesk>
		<template
			v-slot:default="{
				record,
				combos: { parents, subdistricts, villages },
				store,
			}"
		>
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-text-field
							label="Name"
							v-model="record.name"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="parents"
							label="Parent"
							v-model="record.parent_id"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="['OPD', 'KECAMATAN', 'KELURAHAN', 'DESA']"
							label="Tipe"
							v-model="record.scope"
							hide-details
						></v-select>
					</v-col>

					<v-col
						cols="6"
						v-if="['KELURAHAN', 'DESA'].includes(record.scope)"
					>
						<v-combobox
							:items="subdistricts"
							:return-object="false"
							label="Kecamatan"
							v-model="record.subdistrict_id"
							hide-details
							@update:modelValue="
								updateSubdistrict($event, record, store)
							"
						></v-combobox>
					</v-col>

					<v-col
						cols="6"
						v-if="['KELURAHAN', 'DESA'].includes(record.scope)"
					>
						<v-combobox
							:items="villages"
							:return-object="false"
							label="Desa"
							v-model="record.village_id"
							hide-details
						></v-combobox>
					</v-col>
				</v-row>
			</v-card-text>
		</template>
	</form-edit>
</template>

<script>
export default {
	name: "foundation-workunit-edit",

	methods: {
		updateSubdistrict: function (subdistrict, record, store) {
			record.village_id = null;

			this.$http(
				`foundation/api/subdistrict/${subdistrict}/villages`
			).then((response) => {
				store.combos.villages = response;
			});
		},
	},
};
</script>
