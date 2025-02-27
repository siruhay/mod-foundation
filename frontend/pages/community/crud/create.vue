<template>
	<form-create with-helpdesk>
		<template
			v-slot:default="{
				combos: { communitymaps, subdistricts, villages },
				record,
				store,
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
							:return-object="false"
							label="Kecamatan"
							v-model="record.subdistrict_id"
							hide-details
							@update:modelValue="
								updateSubdistrict($event, store)
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
	name: "foundation-community-create",

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
