<template>
	<form-create with-helpdesk>
		<template
			v-slot:default="{
				combos: {
					genders,
					positions,
					subdistricts,
					villages,
				},
				record,
				store,
			}"
		>
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-text-field
							label="Nama"
							v-model="record.name"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="N.I.K"
							v-model="record.slug"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Nomor HP"
							v-model="record.phone"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="positions"
							label="Jabatan"
							v-model="record.position_id"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="genders"
							label="Jenis Kelamin"
							v-model="record.gender_id"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="12">
						<v-combobox
							:items="subdistricts"
							:return-object="false"
							label="Kecamatan"
							v-model="record.subdistrict_id"
							hide-details
							@update:modelValue="
								updateSubdistrict(
									$event,
									store
								)
							"
						></v-combobox>
					</v-col>

					<v-col cols="12">
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
	</form-create>
</template>

<script>
export default {
	name: "foundation-official-create",

	methods: {
		updateSubdistrict: function (subdistrict, store) {
			this.$http(
				`foundation/api/subdistrict/${subdistrict}/villages`
			).then((response) => {
				store.combos.villages = response;
			});
		},
	},
};
</script>
