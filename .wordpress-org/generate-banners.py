#!/usr/bin/env python3
"""Export WordPress.org plugin directory banners from banner-source.png."""

from __future__ import annotations

from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parent
ASSETS = ROOT / "assets"
SOURCE = ASSETS / "banner-source.png"

SIZES = (
	(772, 250, "banner-772x250.jpg"),
	(1544, 500, "banner-1544x500.jpg"),
)


def cover_resize(image: Image.Image, target_w: int, target_h: int) -> Image.Image:
	src_w, src_h = image.size
	scale = max(target_w / src_w, target_h / src_h)
	new_w = int(round(src_w * scale))
	new_h = int(round(src_h * scale))
	resized = image.resize((new_w, new_h), Image.Resampling.LANCZOS)
	left = (new_w - target_w) // 2
	top = (new_h - target_h) // 2
	return resized.crop((left, top, left + target_w, top + target_h))


def main() -> None:
	if not SOURCE.is_file():
		raise SystemExit(f"Missing source image: {SOURCE}")

	ASSETS.mkdir(parents=True, exist_ok=True)
	base = Image.open(SOURCE).convert("RGB")

	for width, height, filename in SIZES:
		path = ASSETS / filename
		cover_resize(base, width, height).save(path, "JPEG", quality=92, optimize=True, progressive=True)
		print(f"Wrote {path} ({width}x{height})")


if __name__ == "__main__":
	main()
