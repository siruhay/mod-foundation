<template>
	<form-show with-helpdesk>
		<template
			v-slot:default="{
				combos: { communitymaps, subdistricts, villages },
				record,
			}"
		>
			<v-card-text>
				<v-row dense>
					<v-col
						:cols="
							record && record.communitymap_id === 4
								? 9
								: record && record.communitymap_id === 5
								? 6
								: 12
						"
					>
						<v-combobox
							:items="communitymaps"
							:return-object="false"
							label="Tipe"
							v-model="record.communitymap_id"
							hide-details
						></v-combobox>
					</v-col>

					<v-col
						cols="3"
						v-if="
							record &&
							(record.communitymap_id === 4 ||
								record.communitymap_id === 5)
						"
					>
						<v-text-field
							label="RW"
							v-model="record.citizen"
							hide-details
						></v-text-field>
					</v-col>

					<v-col
						cols="3"
						v-if="record && record.communitymap_id === 5"
					>
						<v-text-field
							label="RT"
							v-model="record.neighborhood"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="12">
						<v-combobox
							:items="subdistricts"
							label="Kecamatan"
							v-model="record.subdistrict_id"
							hide-details
							readonly
						></v-combobox>
					</v-col>

					<v-col cols="12">
						<v-combobox
							:items="villages"
							label="Desa"
							v-model="record.village_id"
							hide-details
							readonly
						></v-combobox>
					</v-col>
				</v-row>
			</v-card-text>
		</template>

		<template v-slot:info="{ theme }">
			<div class="text-overline mt-4">Aksi</div>
			<v-divider class="mb-3"></v-divider>

			<v-row>
				<v-col cols="12">
					<v-btn
						:color="theme"
						variant="flat"
						block
						@click="
							$router.push({
								name: 'foundation-member',
							})
						"
						>daftar anggota</v-btn
					>
				</v-col>
			</v-row>
		</template>
	</form-show>
</template>

<script>
export default {
	name: "foundation-community-show",
};
</script>
