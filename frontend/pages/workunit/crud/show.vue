<template>
	<form-show with-helpdesk>
		<template v-slot:default="{ record }">
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-text-field
							label="Name"
							v-model="record.name"
							readonly
						></v-text-field>
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
						:disabled="
							!['DESA', 'KELURAHAN'].includes(record.scope)
						"
						:color="theme"
						variant="flat"
						block
						@click="
							$router.push({
								name: 'foundation-workunitcomm',
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
