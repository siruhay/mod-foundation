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

					<v-col cols="6">
						<v-select
							:items="genders"
							label="Jenis Kelamin"
							v-model="record.gender_id"
							hide-details
							readonly
						></v-select>
					</v-col>

					<v-col cols="3">
						<v-text-field
							label="RW"
							v-model="record.citizen"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="3">
						<v-text-field
							label="RT"
							v-model="record.neighborhood"
							hide-details
							readonly
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="scopes"
							label="Bidang Standar Pelayanan Minimal (SPM)"
							v-model="record.scope"
							hide-details
							readonly
						></v-select>
					</v-col>
				</v-row>
			</v-card-text>
		</template>

		<template v-slot:helpdesk></template>
	</form-show>
</template>

<script>
export default {
	name: "foundation-member-show",

	data: () => ({
		scopes: [
			{ title: "Non SPM", value: null },
			{ title: "Pendidikan", value: "PENDIDIKAN" },
			{ title: "Kesehatan", value: "KESEHATAN" },
			{ title: "Pekerjaan Umum", value: "PEKERJAAN-UMUM" },
			{ title: "Perumahan Rakyat", value: "PERUMAHAN-RAKYAT" },
			{
				title: "Ketentraman, Ketertiban Umum dan Perlindungan Masyarakat",
				value: "TRANTIB",
			},
			{ title: "Sosial", value: "SOSIAL" },
		],
	}),
};
</script>
