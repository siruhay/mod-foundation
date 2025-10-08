<template>
	<form-show with-helpdesk>
		<template v-slot:default="{ combos: { genders, positions }, record }">
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
						></v-text-field>
					</v-col>

					<v-col cols="8">
						<v-select
							:items="positions"
							label="Jabatan"
							v-model="record.position_id"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="4">
						<v-select
							:items="genders"
							label="Jenis Kelamin"
							v-model="record.gender_id"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="6">
						<v-combobox
							label="Kota/Kabupaten"
							v-model="record.regency_id"
							hide-details
						></v-combobox>
					</v-col>

					<v-col cols="6">
						<v-combobox
							label="Kecamatan"
							v-model="record.subdistrict_id"
							hide-details
						></v-combobox>
					</v-col>

					<v-col cols="6">
						<v-combobox
							label="Kelurahan/Desa"
							v-model="record.village_id"
							hide-details
						></v-combobox>
					</v-col>

					<v-col cols="3">
						<v-text-field
							label="RW"
							v-model="record.citizen"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="3">
						<v-text-field
							label="RT"
							v-model="record.neighborhood"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-textarea
							label="Alamat"
							rows="2"
							v-model="record.address"
							hide-details
						></v-textarea>
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
