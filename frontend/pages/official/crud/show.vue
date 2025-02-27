<template>
	<form-show with-helpdesk>
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
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="N.I.K"
							v-model="record.slug"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-text-field
							label="Nomor HP"
							v-model="record.phone"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="positions"
							label="Jabatan"
							v-model="record.position_id"
							hide-details
							readonly
						></v-select>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="genders"
							label="Jenis Kelamin"
							v-model="record.gender_id"
							hide-details
							readonly
						></v-select>
					</v-col>

					<v-col cols="12">
						<v-combobox
							:items="subdistricts"
							:return-object="false"
							label="Kecamatan"
							v-model="record.subdistrict_id"
							hide-details
							readonly
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
							readonly
						></v-combobox>
					</v-col>
				</v-row>
			</v-card-text>
		</template>

		<template v-slot:helpdesk></template>
	</form-show>
</template>

<script>
export default {
	name: "foundation-official-show",
};
</script>
