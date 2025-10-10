<template>
	<form-edit with-helpdesk>
		<template v-slot:default="{ combos: { genders, positions, scopes }, record }">
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

					<v-col cols="6">
						<v-select
							:items="genders"
							label="Jenis Kelamin"
							v-model="record.gender_id"
							hide-details
						></v-select>
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
						<v-select
							:items="scopes"
							label="Bidang Standar Pelayanan Minimal (SPM)"
							v-model="record.scope"
							hide-details
						></v-select>
					</v-col>
				</v-row>
			</v-card-text>
		</template>
	</form-edit>
</template>

<script>
export default {
	name: "foundation-member-edit",
};
</script>
