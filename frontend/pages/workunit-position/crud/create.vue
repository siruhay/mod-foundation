<template>
	<form-create with-helpdesk>
		<template v-slot:default="{ combos: { parents, posmaps }, record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-combobox
							:items="posmaps"
							label="Nama"
							v-model="record.posmap"
							hide-details
						></v-combobox>
					</v-col>

					<v-col cols="12">
						<v-select
							:items="['STRUCTURAL', 'FUNCTIONAL', 'EXECUTOR']"
							label="Jenis"
							v-model="record.position_type"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="12">
						<v-combobox
							:items="parents"
							:return-object="false"
							label="Induk"
							v-model="record.parent_id"
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
	name: "foundation-position-create",
};
</script>
