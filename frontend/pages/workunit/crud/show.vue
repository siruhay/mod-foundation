<template>
	<form-show with-helpdesk>
		<template
			v-slot:default="{ record, combos: { parents, subdistricts, villages } }"
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

					<v-col cols="12">
						<v-select
							:items="parents"
							label="Induk"
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

					<v-col cols="6" v-if="['KELURAHAN', 'DESA'].includes(record.scope)">
						<v-combobox
							:items="subdistricts"
							:return-object="false"
							label="Kecamatan"
							v-model="record.subdistrict_id"
							hide-details
						></v-combobox>
					</v-col>

					<v-col cols="6" v-if="['KELURAHAN', 'DESA'].includes(record.scope)">
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

		<template v-slot:info="{ record, theme }">
			<div class="text-overline mt-4">Link</div>
			<v-divider class="mb-3"></v-divider>

			<v-row dense>
				<v-col cols="4">
					<v-btn
						:color="theme"
						variant="flat"
						block
						@click="
							$router.push({
								name: 'foundation-workunitpos',
							})
						"
						>jabatan</v-btn
					>
				</v-col>

				<v-col cols="4">
					<v-btn
						:color="theme"
						variant="flat"
						block
						@click="
							$router.push({
								name: 'foundation-official',
							})
						"
						>pegawai</v-btn
					>
				</v-col>

				<v-col cols="4">
					<v-btn
						:disabled="!['DESA', 'KELURAHAN'].includes(record.scope)"
						:color="theme"
						variant="flat"
						block
						@click="
							$router.push({
								name: 'foundation-workcomm',
							})
						"
						>lembaga</v-btn
					>
				</v-col>
			</v-row>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "foundation-workunit-show",
};
</script>
