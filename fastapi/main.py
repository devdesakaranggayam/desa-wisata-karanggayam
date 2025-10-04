from fastapi import FastAPI, UploadFile, File
import cv2, numpy as np, os

app = FastAPI()

GAPURA_PATH = "../public/gapura_dataset"  # dataset fix

def compare_images(img1, img2):
    # resize biar konsisten
    img1 = cv2.resize(img1, (224,224))
    img2 = cv2.resize(img2, (224,224))

    # histogram warna (8x8x8 bins)
    hist1 = cv2.calcHist([img1], [0,1,2], None, [8,8,8], [0,256,0,256,0,256])
    hist1 = cv2.normalize(hist1, hist1).flatten()

    hist2 = cv2.calcHist([img2], [0,1,2], None, [8,8,8], [0,256,0,256,0,256])
    hist2 = cv2.normalize(hist2, hist2).flatten()

    # compare histograms (correlation, range -1 to 1)
    score = cv2.compareHist(hist1, hist2, cv2.HISTCMP_CORREL)
    return score

@app.post("/check-similarity")
async def check_similarity(file: UploadFile = File(...)):
    # baca user upload
    img_bytes = await file.read()
    nparr = np.frombuffer(img_bytes, np.uint8)
    user_img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

    results = []
    for f in os.listdir(GAPURA_PATH):
        path = os.path.join(GAPURA_PATH, f)
        ref_img = cv2.imread(path)
        if ref_img is None:
            continue
        score = compare_images(user_img, ref_img)
        results.append({"file": f, "score": float(score)})

    # ambil yang paling mirip
    best = max(results, key=lambda x: x["score"])

    # threshold correlation
    threshold = 0.7
    is_match = best["score"] >= threshold

    return {
        "best_match": best,
        "is_match": is_match,
        "threshold": threshold
    }
